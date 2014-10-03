<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ProgressEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Core;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;

/**
 * Provides the bridge between /* Replaced /* Replaced /* Replaced Guzzle */ */ */ requests and responses and /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring.
 */
class RingBridge
{
    /**
     * Creates a Ring request from a request object.
     *
     * This function does not hook up the "then" and "progress" events that
     * would be required for actually sending a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ request through a
     * ring adapter.
     *
     * @param RequestInterface $request Request to convert.
     *
     * @return array Converted /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring request.
     */
    public static function createRingRequest(RequestInterface $request)
    {
        $options = $request->getConfig()->toArray();
        $url = $request->getUrl();
        // No need to calculate the query string twice (in URL and query).
        $qs = ($pos = strpos($url, '?')) ? substr($url, $pos + 1) : null;

        return [
            'scheme'       => $request->getScheme(),
            'http_method'  => $request->getMethod(),
            'url'          => $url,
            'uri'          => $request->getPath(),
            'headers'      => $request->getHeaders(),
            'body'         => $request->getBody(),
            'version'      => $request->getProtocolVersion(),
            '/* Replaced /* Replaced /* Replaced client */ */ */'       => $options,
            'query_string' => $qs,
            'future'       => isset($options['future']) ? $options['future'] : false
        ];
    }

    /**
     * Creates a Ring request from a request object AND prepares the callbacks.
     *
     * @param Transaction $trans Transaction to update.
     *
     * @return array Converted /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring request.
     */
    public static function prepareRingRequest(Transaction $trans)
    {
        // Clear out the transaction state when initiating.
        $trans->exception = null;
        $request = self::createRingRequest($trans->request);

        // Emit progress events if any progress listeners are registered.
        if ($trans->request->getEmitter()->hasListeners('progress')) {
            $emitter = $trans->request->getEmitter();
            $request['/* Replaced /* Replaced /* Replaced client */ */ */']['progress'] = function ($a, $b, $c, $d)
            use ($trans, $emitter)
            {
                $emitter->emit(
                    'progress',
                    new ProgressEvent($trans, $a, $b, $c, $d)
                );
            };
        }

        return $request;
    }

    /**
     * Handles the process of processing a response received from a ring
     * handler. The created response is added to the transaction, and any
     * necessary events are emitted based on the ring response.
     *
     * @param Transaction             $trans          Owns request and response.
     * @param array                   $response       Ring response array
     * @param MessageFactoryInterface $messageFactory Creates response objects.
     * @param Fsm                     $fsm            State machine.
     */
    public static function completeRingResponse(
        Transaction $trans,
        array $response,
        MessageFactoryInterface $messageFactory,
        Fsm $fsm
    ) {
        $trans->state = 'complete';
        $trans->transferInfo = isset($response['transfer_info'])
            ? $response['transfer_info'] : [];

        if (!empty($response['status'])) {
            $options = [];
            if (isset($response['version'])) {
                $options['protocol_version'] = $response['version'];
            }
            if (isset($response['reason'])) {
                $options['reason_phrase'] = $response['reason'];
            }
            $trans->response = $messageFactory->createResponse(
                $response['status'],
                isset($response['headers']) ? $response['headers'] : [],
                isset($response['body']) ? $response['body'] : null,
                $options
            );
            if (isset($response['effective_url'])) {
                $trans->response->setEffectiveUrl($response['effective_url']);
            }
        } elseif (empty($response['error'])) {
            // When nothing was returned, then we need to add an error.
            $response['error'] = self::getNoRingResponseException($trans->request);
        }

        if (isset($response['error'])) {
            $trans->state = 'error';
            $trans->exception = $response['error'];
        }

        // Complete the lifecycle of the request.
        $fsm->run($trans);
    }

    /**
     * Creates a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ request object using a ring request array.
     *
     * @param array $request Ring request
     *
     * @return Request
     * @throws \InvalidArgumentException for incomplete requests.
     */
    public static function fromRingRequest(array $request)
    {
        $options = [];
        if (isset($request['version'])) {
            $options['protocol_version'] = $request['version'];
        }

        if (!isset($request['http_method'])) {
            throw new \InvalidArgumentException('No http_method');
        }

        return new Request(
            $request['http_method'],
            Core::url($request),
            isset($request['headers']) ? $request['headers'] : [],
            isset($request['body']) ? Stream::factory($request['body']) : null,
            $options
        );
    }

    /**
     * Get an exception that can be used when a ring adapter does not populate
     * a response.
     *
     * @param RequestInterface $request
     *
     * @return RequestException
     */
    public static function getNoRingResponseException(RequestInterface $request)
    {
        $message = <<<EOT
Sending the request did not return a response, exception, or populate the
transaction with a response. This is most likely due to an incorrectly
implemented /* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring adapter. If you are simply trying to mock responses,
then it is recommneded to use the /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter.
EOT;
        return new RequestException($message, $request);
    }
}
