<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\TransactionInterface;

/**
 * Event object emitted after a request has been sent and an error was encountered
 *
 * You may intercept the exception and inject a response into the event to rescue the request.
 */
class RequestErrorEvent extends AbstractTransferStatsEvent
{
    private $exception;

    /**
     * @param TransactionInterface $transaction   Transaction that contains the request
     * @param RequestException     $e             Exception encountered
     * @param array                $transferStats Array of transfer statistics
     */
    public function __construct(TransactionInterface $transaction, RequestException $e, $transferStats = [])
    {
        parent::__construct($transaction, $transferStats);
        $this->exception = $e;
    }

    /**
     * Intercept the exception and inject a response
     *
     * @param ResponseInterface $response Response to set
     */
    public function intercept(ResponseInterface $response)
    {
        $this->stopPropagation();
        $this->getTransaction()->setResponse($response);
        RequestEvents::emitAfterSendEvent($this->getTransaction());
    }

    /**
     * Get the exception that was encountered
     *
     * @return RequestException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Get the response the was received (if any)
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->getException()->getResponse();
    }
}
