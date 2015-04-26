<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise as P;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\Promise;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use Psr\Http\Message\RequestInterface;

/**
 * Returns an asynchronous response using curl_multi_* functions.
 *
 * When using the CurlMultiHandler, custom curl options can be specified as an
 * associative array of curl option constants mapping to values in the
 * **curl** key of the provided request options.
 *
 * @property resource $_mh Internal use only. Lazy loaded multi-handle.
 */
class CurlMultiHandler
{
    /** @var callable */
    private $factory;
    private $selectTimeout;
    private $active;
    private $handles = [];
    private $delays = [];

    /**
     * This handler accepts the following options:
     *
     * - handle_factory: An optional callable used to generate curl handle
     *   resources. the callable accepts a request hash and returns an array
     *   of the handle, headers file resource, and the body resource.
     * - select_timeout: Optional timeout (in seconds) to block before timing
     *   out while selecting curl handles. Defaults to 1 second.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->factory = isset($options['handle_factory'])
            ? $options['handle_factory'] : new CurlFactory();
        $this->selectTimeout = isset($options['select_timeout'])
            ? $options['select_timeout'] : 1;
    }

    public function __get($name)
    {
        if ($name === '_mh') {
            return $this->_mh = curl_multi_init();
        }

        throw new \BadMethodCallException();
    }

    public function __destruct()
    {
        if (isset($this->_mh)) {
            curl_multi_close($this->_mh);
            unset($this->_mh);
        }
    }

    public function __invoke(RequestInterface $request, array $options)
    {
        $factory = $this->factory;
        $easy = $factory($request, $options);
        $id = (int) $easy->handle;

        $promise = new Promise(
            [$this, 'execute'],
            function () use ($id) { return $this->cancel($id); }
        );

        $entry = [
            'request'  => $request,
            'options'  => $options,
            'easy'     => $easy,
            'deferred' => $promise,
        ];

        $this->addRequest($entry);

        return $promise;
    }

    /**
     * Ticks the curl event loop.
     */
    public function tick()
    {
        // Add any delayed handles if needed.
        if ($this->delays) {
            $currentTime = microtime(true);
            foreach ($this->delays as $id => $delay) {
                if ($currentTime >= $delay) {
                    unset($this->delays[$id]);
                    curl_multi_add_handle(
                        $this->_mh,
                        $this->handles[$id]['easy']->handle
                    );
                }
            }
        }

        // Step through the task queue which may add additional requests.
        P\queue()->run();

        if ($this->active &&
            curl_multi_select($this->_mh, $this->selectTimeout) === -1
        ) {
            // Perform a usleep if a select returns -1.
            // See: https://bugs.php.net/bug.php?id=61141
            usleep(250);
        }

        while (curl_multi_exec($this->_mh, $this->active) === CURLM_CALL_MULTI_PERFORM);

        $this->processMessages();
    }

    /**
     * Runs until all outstanding connections have completed.
     */
    public function execute()
    {
        $queue = P\queue();

        while ($this->handles || !$queue->isEmpty()) {
            // If there are no transfers, then sleep for the next delay
            if (!$this->active && $this->delays) {
                usleep($this->timeToNext());
            }
            $this->tick();
        }
    }

    private function addRequest(array $entry)
    {
        $easy = $entry['easy'];
        $id = (int) $easy->handle;
        $this->handles[$id] = $entry;
        if (empty($entry['options']['delay'])) {
            curl_multi_add_handle($this->_mh, $easy->handle);
        } else {
            $this->delays[$id] = microtime(true) + ($entry['options']['delay'] / 1000);
        }
    }

    /**
     * Cancels a handle from sending and removes references to it.
     *
     * @param int $id Handle ID to cancel and remove.
     *
     * @return bool True on success, false on failure.
     */
    private function cancel($id)
    {
        // Cannot cancel if it has been processed.
        if (!isset($this->handles[$id])) {
            return false;
        }

        $handle = $this->handles[$id]['easy']->handle;
        unset($this->delays[$id], $this->handles[$id]);
        curl_multi_remove_handle($this->_mh, $handle);
        curl_close($handle);

        return true;
    }

    private function processMessages()
    {
        while ($done = curl_multi_info_read($this->_mh)) {

            $id = (int) $done['handle'];

            if (!isset($this->handles[$id])) {
                // Probably was cancelled.
                continue;
            }

            $entry = $this->handles[$id];
            unset($this->handles[$id], $this->delays[$id]);

            $response = [];
            if ($done['result'] !== CURLM_OK) {
                $response['curl']['errno'] = $done['result'];
                $response['curl']['error'] = curl_strerror($done['result']);
            }

            $easy = $entry['easy'];
            $result = CurlFactory::createResponse(
                $this,
                $entry['request'],
                $entry['options'],
                $response,
                $easy->headers,
                /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for($easy->body)
            );

            curl_multi_remove_handle($this->_mh, $done['handle']);
            curl_close($done['handle']);
            $entry['deferred']->resolve($result);
        }
    }

    private function timeToNext()
    {
        $currentTime = microtime(true);
        $nextTime = PHP_INT_MAX;
        foreach ($this->delays as $time) {
            if ($time < $nextTime) {
                $nextTime = $time;
            }
        }

        return max(0, $currentTime - $nextTime);
    }
}
