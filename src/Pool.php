<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromisorInterface;
use Psr\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\EachPromise;

/**
 * Sends and iterator of requests concurrently using a capped pool size.
 *
 * The pool will read from an iterator until it is cancelled or until the
 * iterator is consumed. When a request is yielded, the request is sent after
 * applying the "request_options" request options (if provided in the ctor).
 *
 * When a function is yielded by the iterator, the function is provided the
 * "request_options" array that should be merged on top of any existing
 * options, and the function MUST then return a wait-able promise.
 */
class Pool implements PromisorInterface
{
    /** @var EachPromise */
    private $each;

    /**
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the requests.
     * @param array|\Iterator $requests Requests or functions that return
     *                                  requests to send concurrently.
     * @param array           $config   Associative array of options
     *     - limit: (int) Maximum number of requests to send concurrently
     *     - options: Array of request options to apply to each request.
     *     - onFulfilled: (callable) Function to invoke when a request
     *       completes.
           - onRejected: (callable) Function to invoke when a request is
     *       rejected.
     */
    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        $requests,
        array $config = []
    ) {
        // Backwards compatibility.
        if (isset($config['pool_size'])) {
            $config['limit'] = $config['pool_size'];
        }

        if (!isset($config['limit'])) {
            $config['limit'] = 25;
        }

        if (isset($config['options'])) {
            $opts = $config['options'];
            unset($config['options']);
        } else {
            $opts = [];
        }

        $config['mapfn'] = function ($requestOrFunction) use ($/* Replaced /* Replaced /* Replaced client */ */ */, $opts) {
            if ($requestOrFunction instanceof RequestInterface) {
                return $/* Replaced /* Replaced /* Replaced client */ */ */->send($requestOrFunction, $opts);
            } elseif (is_callable($requestOrFunction)) {
                return $requestOrFunction($opts);
            }
            throw new \InvalidArgumentException('Each value yielded by the '
                . 'iterator must be a /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Http\Message\RequestInterface or '
                . 'a callable that returns a promise that fulfills with a '
                . '/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Message\Http\ResponseInterface object.');
        };

        $this->each = new EachPromise($requests, $config);
    }

    public function promise()
    {
        return $this->each->promise();
    }

    /**
     * Sends multiple requests concurrently and returns an array of responses
     * and exceptions that uses the same ordering as the provided requests.
     *
     * IMPORTANT: This method keeps every request and response in memory, and
     * as such, is NOT recommended when sending a large number or an
     * indeterminate number of requests concurrently.
     *
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the requests
     * @param array|\Iterator $requests Requests to send concurrently.
     * @param array           $options  Passes through the options available in
     *                                  {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool::__construct}
     *
     * @return array Returns an array containing the response or an exception
     *               in the same order that the requests were sent.
     * @throws \InvalidArgumentException if the event format is incorrect.
     */
    public static function batch(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        $requests,
        array $options = []
    ) {
        $res = [];
        self::cmpCallback($options, 'onFulfilled', $res);
        self::cmpCallback($options, 'onRejected', $res);
        $pool = new static($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, $options);
        $pool->promise()->wait();
        ksort($res);

        return $res;
    }

    private static function cmpCallback(array &$options, $name, array &$results)
    {
        if (!isset($options[$name])) {
            $options[$name] = function ($v, $k) use (&$results) {
                $results[$k] = $v;
            };
        } else {
            $currentFn = $options[$name];
            $options[$name] = function ($v, $k) use (&$results, $currentFn) {
                $currentFn($v, $k);
                $results[$k] = $v;
            };
        }
    }
}
