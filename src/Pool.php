<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Core;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\FutureInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ListenerAttacherTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EndEvent;

/**
 * Sends and iterator of requests concurrently using a capped pool size.
 *
 * The Pool object implements FutureInterface, meaning it can be used later
 * when necessary, the requests provided to the pool can be cancelled, and
 * you can check the state of the pool to know if it has been dereferenced
 * (sent) or has been cancelled.
 *
 * When sending the pool, keep in mind that no results are returned: callers
 * are expected to handle results asynchronously using /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s event system.
 * When requests complete, more are added to the pool to ensure that the
 * requested pool size is always filled as much as possible.
 *
 * IMPORTANT: Do not provide a pool size greater that what the utilized
 * underlying /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring adapter can support. This will result is extremely
 * poor performance.
 */
class Pool implements FutureInterface
{
    use ListenerAttacherTrait;

    /** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var array Hash of outstanding responses to dereference. */
    private $derefQueue = [];

    /** @var \Iterator Yields requests */
    private $iter;

    /** @var int */
    private $poolSize;

    /** @var bool */
    private $isCancelled = false;

    /** @var array Listeners to attach to each request */
    private $eventListeners = [];

    /**
     * The option values for 'before', 'after', and 'error' can be a callable,
     * an associative array containing event data, or an array of event data
     * arrays. Event data arrays contain the following keys:
     *
     * - fn: callable to invoke that receives the event
     * - priority: Optional event priority (defaults to 0)
     * - once: Set to true so that the event is removed after it is triggered
     *
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the requests.
     * @param array|\Iterator $requests Requests to send in parallel
     * @param array           $options  Associative array of options
     *     - pool_size: (int) Maximum number of requests to send concurrently
     *     - before:    (callable|array) Receives a BeforeEvent
     *     - after:     (callable|array) Receives a CompleteEvent
     *     - error:     (callable|array) Receives a ErrorEvent
     */
    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        $requests,
        array $options = []
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->iter = $this->coerceIterable($requests);
        $this->poolSize = isset($options['pool_size'])
            ? $options['pool_size'] : 25;
        $this->eventListeners = $this->prepareListeners(
            $this->prepareOptions($options),
            ['before', 'complete', 'error', 'end']
        );
    }

    /**
     * Creates and immediately transfers a Pool object.
     *
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the requests.
     * @param array|\Iterator $requests Requests to send in parallel
     * @param array           $options  Associative array of options
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool::__construct for the list of available options.
     */
    public static function send(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        $requests,
        array $options = []
    ) {
        (new self($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, $options))->deref();
    }

    /**
     * Sends multiple requests in parallel and returns an array of responses
     * and exceptions that uses the same ordering as the provided requests.
     *
     * IMPORTANT: This method keeps every request and response in memory, and
     * as such, is NOT recommended when sending a large number or an
     * indeterminate number of requests concurrently.
     *
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the requests
     * @param array|\Iterator $requests Requests to send in parallel
     * @param array           $options  Passes through the options available in
     *                                  {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool::__construct}
     *
     * @return array Array of {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface} if
     *     a request succeeded or a {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException}
     *     if it failed. The order of the resulting array is the same order as
     *     the requests that were provided.
     * @throws \InvalidArgumentException if the event format is incorrect.
     */
    public static function batch(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        $requests,
        array $options = []
    ) {
        $hash = new \SplObjectStorage();
        foreach ($requests as $request) {
            $hash->attach($request);
        }

        // In addition to the normally run events when requests complete, add
        // and event to continuously track the results of transfers in the hash.
        static::send($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, RequestEvents::convertEventArray(
            $options,
            ['end'],
            [
                'priority' => RequestEvents::LATE,
                'fn'       => function (EndEvent $e) use ($hash) {
                    $hash[$e->getRequest()] = $e->getException()
                        ? $e->getException()
                        : $e->getResponse();
                }
            ]
        ));

        return iterator_to_array($hash);
    }

    public function realized()
    {
        return !$this->iter || $this->cancelled();
    }

    public function deref()
    {
        if ($this->realized()) {
            return false;
        }

        // Seed the pool with N number of requests.
        // @todo: Is there way to stop seeding when the adapter auto-flushes?
        for ($i = 0; $i < $this->poolSize; $i++) {
            if (!$this->addNextRequest()) {
                break;
            }
        }

        // Stop if the pool was cancelled while transferring requests.
        if ($this->isCancelled) {
            return false;
        }

        // Dereference any outstanding FutureResponse objects.
        while ($response = array_pop($this->derefQueue)) {
            $response->deref();
        }

        // Clean up no longer needed state.
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $this->iter = $this->derefQueue = $this->eventListeners = null;

        return true;
    }

    public function cancelled()
    {
        return $this->isCancelled;
    }

    public function cancel()
    {
        if ($this->isCancelled || $this->realized()) {
            return false;
        }

        // Return true if ALL in-flight requests could be cancelled.
        $success = $this->isCancelled = true;
        foreach ($this->derefQueue as $response) {
            if (!$response->cancel()) {
                $success = false;
            }
        }

        return $success;
    }

    private function coerceIterable($requests)
    {
        if ($requests instanceof \Iterator) {
            return $requests;
        } elseif (is_array($requests)) {
            return new \ArrayIterator($requests);
        }

        throw new \InvalidArgumentException('Expected Iterator or array. '
            . 'Found ' . Core::describeType($requests));
    }

    private function prepareOptions(array $options)
    {
        // Add the next request when requests finish.
        $options = RequestEvents::convertEventArray($options, ['end'], [
            'priority' => RequestEvents::EARLY,
            'fn'       => function ($e) {
                unset($this->derefQueue[spl_object_hash($e->getRequest())]);
                $this->addNextRequest();
            }
        ]);

        // Stop errors from throwing by intercepting with a future that throws
        // when accessed.
        return RequestEvents::convertEventArray($options, ['end'], [
            'priority' => RequestEvents::LATE - 1,
            'fn'       => function (EndEvent $e) {
                if ($e->getException()) {
                    RequestEvents::stopException($e);
                }
            }
        ]);
    }

    private function addNextRequest()
    {
        if ($this->isCancelled || !$this->iter->valid()) {
            return false;
        }

        $request = $this->iter->current();
        $this->iter->next();

        if (!($request instanceof RequestInterface)) {
            throw new \RuntimeException(sprintf(
                'All requests in the provided iterator must implement '
                . 'RequestInterface. Found %s',
                Core::describeType($request)
            ));
        }

        // Be sure to use "lazy" futures, meaning they do not send right away.
        $request->getConfig()->set('future', 'lazy');
        $this->attachListeners($request, $this->eventListeners);
        $response = $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($request);

        // Track future responses for later dereference before completing pool.
        if ($response instanceof FutureResponse) {
            $this->derefQueue[spl_object_hash($request)] = $response;
        }

        return true;
    }
}
