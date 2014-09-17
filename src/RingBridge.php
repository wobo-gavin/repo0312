<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ProgressEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;

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
     * Give a ring request array, this function adds the "then" and "progress"
     * event callbacks using a transaction and message factory.
     *
     * @param array                   $ringRequest Request to update.
     * @param Transaction             $trans       Transaction
     * @param MessageFactoryInterface $factory     Creates responses.
     *
     * @return array Returns the new ring response array.
     */
    public static function addRingRequestCallbacks(
        array $ringRequest,
        Transaction $trans,
        MessageFactoryInterface $factory
    ) {
        $request = $trans->request;

        $ringRequest['then'] = function (array $response) use ($trans, $factory) {
            self::completeRingResponse($trans, $response, $factory);
        };

        // Emit progress events if any progress listeners are registered.
        if ($request->getEmitter()->hasListeners('progress')) {
            $emitter = $request->getEmitter();
            $ringRequest['/* Replaced /* Replaced /* Replaced client */ */ */']['progress'] = function ($a, $b, $c, $d)
                use ($trans, $emitter)
            {
                $emitter->emit(
                    'progress',
                    new ProgressEvent($trans, $a, $b, $c, $d)
                );
            };
        }

        return $ringRequest;
    }

    /**
     * Creates a Ring request from a request object AND prepares the callbacks.
     *
     * @param Transaction             $transaction Transaction to update.
     * @param MessageFactoryInterface $factory     Creates responses.
     *
     * @return array Converted /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring request.
     */
    public static function prepareRingRequest(
        Transaction $transaction,
        MessageFactoryInterface $factory
    ) {
        return self::addRingRequestCallbacks(
            self::createRingRequest($transaction->request),
            $transaction,
            $factory
        );
    }

    /**
     * Handles the process of processing a response received from a ring
     * handler. The created response is added to the transaction, and any
     * necessary events are emitted based on the ring response.
     *
     * @param Transaction             $trans          Owns request and response.
     * @param array                   $response       Ring response array
     * @param MessageFactoryInterface $messageFactory Creates response objects.
     */
    public static function completeRingResponse(
        Transaction $trans,
        array $response,
        MessageFactoryInterface $messageFactory
    ) {
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
                $response['headers'],
                isset($response['body']) ? $response['body'] : null,
                $options
            );
            if (isset($response['effective_url'])) {
                $trans->response->setEffectiveUrl($response['effective_url']);
            }
        }

        if (!isset($response['error'])) {
            RequestEvents::emitComplete($trans);
        } else {
            RequestEvents::emitError(
                $trans,
                new RequestException(
                    $response['error']->getMessage(),
                    $trans->request,
                    $trans->response,
                    $response['error']
                ),
                isset($response['transfer_info'])
                    ? $response['transfer_info'] : []
            );
        }
    }
}
