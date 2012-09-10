<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Strategy that will invoke a closure to determine whether or not to retry with a delay
 */
class CallbackBackoffStrategy extends AbstractBackoffStrategy
{
    /**
     * @var \Closure|array|mixed Callable method to invoke
     */
    protected $callback;

    /**
     * @var bool Whether or not this strategy makes a retry decision
     */
    protected $decision = false;

    /**
     * @param \Closure|array|mixed     $callback Callable method to invoke
     * @param bool                     $decision Set to true if this strategy makes a backoff decision
     * @param BackoffStrategyInterface $next     The optional next strategy
     */
    public function __construct($callback, $decision, BackoffStrategyInterface $next = null)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('The callback must be callable');
        }
        $this->callback = $callback;
        $this->decision = $decision;
        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function makesDecision()
    {
        return $this->decision;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        $callback = $this->callback;

        return $callback($retries, $request, $response, $e);
    }
}
