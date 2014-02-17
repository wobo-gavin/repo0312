<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\AdapterException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Url;

/**
 * Client interface for sending HTTP requests
 */
interface ClientInterface extends HasEmitterInterface
{
    /**
     * Create and return a new {@see RequestInterface} object.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a
     * relative path to append to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string           $method  HTTP method
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return RequestInterface
     */
    public function createRequest($method, $url = null, array $options = []);

    /**
     * Send a GET request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function get($url = null, $options = []);

    /**
     * Send a HEAD request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function head($url = null, array $options = []);

    /**
     * Send a DELETE request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function delete($url = null, array $options = []);

    /**
     * Send a PUT request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function put($url = null, array $options = []);

    /**
     * Send a PATCH request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function patch($url = null, array $options = []);

    /**
     * Send an OPTIONS request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply. {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface}
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function options($url = null, array $options = []);

    /**
     * Sends a single request
     *
     * @param RequestInterface $request Request to send
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface
     * @throws \LogicException When the adapter does not populate a response
     * @throws RequestException When an error is encountered (network or HTTP errors)
     */
    public function send(RequestInterface $request);

    /**
     * Sends multiple requests in parallel.
     *
     * Exceptions are not thrown for failed requests. Callers are expected to
     * register an "error" option to handle request errors OR directly register
     * an event handler for the "error" event of a request's
     * event emitter.
     *
     * @param array|\Iterator $requests Requests to send in parallel
     * @param array           $options  Associative array of options
     *     - parallel: (int) Max number of requests to send in parallel
     *     - before: (callable) Receives a BeforeEvent
     *     - after: (callable) Receives a CompleteEvent
     *     - error: (callable) Receives a ErrorEvent
     * @throws AdapterException When an error occurs in the HTTP adapter.
     */
    public function sendAll($requests, array $options = []);

    /**
     * Get a /* Replaced /* Replaced /* Replaced client */ */ */ configuration value.
     *
     * @param string|int|null $keyOrPath The Path to a particular configuration value. The syntax uses a path notation
     *                                   that allows you to retrieve nested array values without throwing warnings.
     *                                   For example, ``$/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('defaults/headers/content-type')``.
     * @return mixed
     */
    public function getConfig($keyOrPath = null);

    /**
     * Set a /* Replaced /* Replaced /* Replaced client */ */ */ configuration value at the specified configuration path.
     *
     * Any value can be set for any path, but the common values are ``base_url`` and ``defaults``.
     *
     * @param string|int $keyOrPath Path at which to change a configuration value. This path syntax follows the same
     *                              path syntax specified in {@see getConfig}.
     * @param mixed $value Value to set
     */
    public function setConfig($keyOrPath, $value);
}
