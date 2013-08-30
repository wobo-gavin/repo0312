<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\AdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\BatchAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\AdapterException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;

/**
 * HTTP adapter that uses cURL as a transport layer
 */
class CurlAdapter implements AdapterInterface, BatchAdapterInterface
{
    /** @var CurlFactory */
    private $factory;
    /** @var array Array of curl multi handles */
    private $multiHandles = array();
    /** @var array Array of curl multi handles */
    private $multiOwned = array();
    /** @var MessageFactoryInterface */
    private $messageFactory;

    /**
     * @param MessageFactoryInterface $messageFactory
     * @param array                   $options Array of options to use with the adapter
     *                                         - handle_factory: Optional factory used to create cURL handles
     */
    public function __construct(MessageFactoryInterface $messageFactory, array $options = [])
    {
        $this->messageFactory = $messageFactory;
        $this->factory = isset($options['handle_factory']) ? $options['handle_factory'] : new CurlFactory();
    }

    /**
     * Destroys each open multi handle
     */
    public function __destruct()
    {
        foreach ($this->multiHandles as $handle) {
            if (is_resource($handle)) {
                curl_multi_close($handle);
            }
        }
    }

    public function send(Transaction $transaction)
    {
        if ($transaction->getRequest()->getConfig()['future']) {
            $transaction->getRequest()->getConfig()->set('future', false);
            $response = new FutureResponse($transaction, $this);
            $transaction->setResponse($response);
            return $response;
        }

        $this->batch([$transaction]);

        return $transaction->getResponse();
    }

    public function batch(array $transactions)
    {
        $context = [
            'transactions' => $transactions,
            'handles'      => new \SplObjectStorage(),
            'multi'        => $this->checkoutMultiHandle(),
            'errors'       => []
        ];

        foreach ($transactions as $transaction) {
            try {
                $this->prepare($transaction, $context);
            } catch (RequestException $e) {
                $context['errors'][] = [$transaction, $e];
            }
        }

        $this->perform($context);
        $this->releaseMultiHandle($context['multi']);

        if ($context['errors']) {
            // @TODO throw BatchException
        }
    }

    private function prepare(Transaction $transaction, array $context)
    {
        $handle = $this->factory->createHandle($transaction, $this->messageFactory);
        $this->checkCurlResult(curl_multi_add_handle($context['multi'], $handle));
        $context['handles'][$transaction] = $handle;
    }

    /**
     * Execute and select curl handles
     *
     * @param array $context Transaction context
     */
    private function perform(array $context)
    {
        // The first curl_multi_select often times out no matter what, but is usually required for fast transfers
        $selectTimeout = 0.001;
        $active = false;
        do {
            while (($mrc = curl_multi_exec($context['multi'], $active)) == CURLM_CALL_MULTI_PERFORM);
            $this->checkCurlResult($mrc);
            $this->processMessages($context);
            if ($active && curl_multi_select($context['multi'], $selectTimeout) === -1) {
                // Perform a usleep if a select returns -1: https://bugs.php.net/bug.php?id=61141
                usleep(150);
            }
            $selectTimeout = 1;
        } while ($active);
    }

    /**
     * Check for errors and fix headers of a request based on a curl response
     *
     * @param Transaction $transaction Transaction to process
     * @param array       $curl        Curl data
     * @param array       $context     Array of context information of the transfer
     *
     * @throws RequestException on error
     */
    private function processResponse(Transaction $transaction, array $curl, array $context)
    {
        if (isset($context['handles'][$transaction])) {
            curl_multi_remove_handle($context['multi'], $context['handles'][$transaction]);
            curl_close($context['handles'][$transaction]);
            unset($context['handles'][$transaction]);
        }

        $request = $transaction->getRequest();

        try {
            $this->isCurlException($request, $curl);
            $request->getEventDispatcher()->dispatch(
                'request.after_send',
                new RequestAfterSendEvent($transaction)
            );
        } catch (RequestException $e) {
            if (!$request->getEventDispatcher()->dispatch(
                'request.error',
                new RequestErrorEvent($transaction, $e)
            )->isPropagationStopped()) {
                $context['errors'][] = [$transaction, $e];
            }
        }
    }

    /**
     * Process any received curl multi messages
     */
    private function processMessages(array $context)
    {
        while ($done = curl_multi_info_read($context['multi'])) {
            foreach ($context['handles'] as $transaction) {
                if ($context['handles'][$transaction] === $done['handle']) {
                    $this->processResponse($transaction, $done, $context);
                    continue 2;
                }
            }
        }
    }

    /**
     * Check if a cURL transfer resulted in what should be an exception
     *
     * @param RequestInterface $request Request to check
     * @param array            $curl    Array returned from curl_multi_info_read
     *
     * @throws RequestException|bool
     */
    private function isCurlException(RequestInterface $request, array $curl)
    {
        if (CURLM_OK == $curl['result'] || CURLM_CALL_MULTI_PERFORM == $curl['result']) {
            return;
        }

        // Emit request.error?

        throw new RequestException(
            sprintf('[curl] Error code %s [url] %s', $curl['result'], $request->getUrl()),
            $request
        );
    }

    /**
     * Throw an exception for a cURL multi response if needed
     *
     * @param int $code Curl response code
     * @throws AdapterException
     */
    private function checkCurlResult($code)
    {
        if ($code != CURLM_OK && $code != CURLM_CALL_MULTI_PERFORM) {
            $buffer = function_exists('curl_multi_strerror')
                ? curl_multi_strerror($code)
                : 'See http://curl.haxx.se/libcurl/c/libcurl-errors.html for an explanation of cURL errors';
            throw new AdapterException(sprintf('cURL error %s: %s', $code, $buffer));
        }
    }

    /**
     * Returns a curl_multi handle from the cache or creates a new one
     *
     * @return resource
     */
    private function checkoutMultiHandle()
    {
        // Find an unused handle in the cache
        if (false !== ($key = array_search(false, $this->multiOwned, true))) {
            $this->multiOwned[$key] = true;
            return $this->multiHandles[$key];
        }

        // Add a new handle
        $handle = curl_multi_init();
        $this->multiHandles[(int) $handle] = $handle;
        $this->multiOwned[(int) $handle] = true;

        return $handle;
    }

    /**
     * Releases a curl_multi handle back into the cache and removes excess cache
     *
     * @param resource $handle Curl multi handle to remove
     */
    private function releaseMultiHandle($handle)
    {
        $this->multiOwned[(int) $handle] = false;
        // Prune excessive handles
        $over = count($this->multiHandles) - 3;
        while (--$over > -1) {
            curl_multi_close(array_pop($this->multiHandles));
            array_pop($this->multiOwned);
        }
    }
}
