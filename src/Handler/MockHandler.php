<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Handler that returns responses or throw exceptions from a queue.
 */
class MockHandler implements \Countable
{
    private $queue;
    private $lastRequest;
    private $onFulfilled;
    private $onRejected;

    /**
     * The passed in value must be an array of
     * {@see /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Http\Message\ResponseInterface} objects, Exceptions,
     * callables, or Promises.
     *
     * @param array|ResponseInterface|\Exception|callable|PromiseInterface $queue
     * @param callable $onFulfilled Callback to invoke when the return value is fulfilled.
     * @param callable $onRejected  Callback to invoke when the return value is rejected.
     */
    public function __construct(
        $queue = null,
        callable $onFulfilled = null,
        callable $onRejected = null
    ) {
        $this->onFulfilled = $onFulfilled;
        $this->onRejected = $onRejected;

        if (is_array($queue)) {
            call_user_func_array([$this, 'append'], $queue);
        } elseif ($queue) {
            $this->append($queue);
        }
    }

    public function __invoke(RequestInterface $request, array $options)
    {
        if (!$this->queue) {
            throw new \RuntimeException('Mock queue is empty');
        }

        if (isset($options['delay'])) {
            usleep($options['delay'] * 1000);
        }

        $response = array_shift($this->queue);

        if (is_callable($response)) {
            $response = $response($request, $options);
        }

        $response = $response instanceof \Exception
            ? new RejectedPromise($response)
            : \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\promise_for($response);

        $response->then($this->onFulfilled, $this->onRejected);

        return $response;
    }

    /**
     * Adds one or more variadic requests, exceptions, callables, or promises
     * to the queue.
     */
    public function append()
    {
        foreach (func_get_args() as $value) {
            if ($value instanceof ResponseInterface
                || $value instanceof \Exception
                || $value instanceof PromiseInterface
                || is_callable($value)
            ) {
                $this->queue[] = $value;
            } else {
                throw new \InvalidArgumentException('Expected a response or '
                    . 'exception. Found ' . \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\describe_type($value));
            }
        }
    }

    /**
     * Get the last received request.
     *
     * @return RequestInterface
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Returns the number of remaining items in the queue.
     *
     * @return int
     */
    public function count()
    {
        return count($this->queue);
    }
}
