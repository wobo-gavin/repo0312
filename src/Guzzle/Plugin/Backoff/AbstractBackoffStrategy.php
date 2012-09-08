<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Abstract backoff strategy that allows for a chain of responsibility
 */
abstract class AbstractBackoffStrategy implements BackoffStrategyInterface
{
    /**
     * @var BackoffStrategyInterface Next strategy in the chain
     */
    protected $next;

    /**
     * @param BackoffStrategyInterface $next Next strategy in the chain
     */
    public function setNext(BackoffStrategyInterface $next)
    {
        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function getBackoffPeriod(
        $retries,
        RequestInterface $request,
        Response $response = null,
        HttpException $e = null
    ) {
        $delay = $this->getDelay($retries, $request, $response, $e);
        if ($delay === false) {
            return false;
        } elseif ($delay === true || $delay === null) {
            return $this->next ? $this->next->getBackoffPeriod($retries, $request, $response, $e) : 0;
        } else {
            return $delay;
        }
    }

    /**
     * Implement the concrete strategy
     *
     * @param int              $retries  Number of retries of the request
     * @param RequestInterface $request  Request that was sent
     * @param Response         $response Response that was received. Note that there may not be a response
     * @param HttpException    $e        Exception that was encountered if any
     *
     * @return bool|int|null Returns false to not retry or the number of seconds to delay between retries. Return true
     *                       or null to defer to the next strategy if available, and if not, return 0.
     */
    abstract protected function getDelay(
        $retries,
        RequestInterface $request,
        Response $response = null,
        HttpException $e = null
    );
}
