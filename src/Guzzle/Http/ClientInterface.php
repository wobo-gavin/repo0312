<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\TransferException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\Url;

/**
 * Client interface for sending HTTP requests
 */
interface ClientInterface extends HasDispatcherInterface
{
    /**
     * Create and return a new {@see RequestInterface} object.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a
     * relative path to append to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string                          $method  HTTP method
     * @param string|array                    $url     Resource URL
     * @param array                           $headers Request headers
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Array of options to apply to the request
     *
     * @return RequestInterface
     */
    public function createRequest($method, $url = null, array $headers = [], $body = null, array $options = []);

    /**
     * Send a GET request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $headers Request headers
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function get($url = null, array $headers = [], $options = []);

    /**
     * Send a HEAD request
     *
     * @param string|array|Url $url     Absolute or relative URL
     * @param array            $headers Request headers
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function head($url = null, array $headers = [], array $options = []);

    /**
     * Send a DELETE request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $headers Request headers
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function delete($url = null, array $headers = [], array $options = []);

    /**
     * Send a PUT request
     *
     * @param string|array|Url                $url     Resource URL
     * @param array                           $headers Request headers
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function put($url = null, array $headers = [], $body = null, array $options = []);

    /**
     * Send a PATCH request
     *
     * @param string|array|Url                $url     Resource URL
     * @param array                           $headers Request headers
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function patch($url = null, array $headers = [], $body = null, array $options = []);

    /**
     * Send an OPTIONS request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $headers Request headers
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     */
    public function options($url = null, array $headers = [], array $options = []);

    /**
     * Sends a single request
     *
     * @param RequestInterface $request Request to send
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface
     * @throws \LogicException When the adapter does not populate a response
     * @throws TransferException When an error is encountered (network or HTTP errors)
     */
    public function send(RequestInterface $request);

    /**
     * Sends multiple requests in parallel.
     *
     * Exceptions are not thrown for failed requests. Callers are expected to
     * register an "error" option to handle request errors OR directly register
     * an event handler for the "request.error" event of a request's
     * EventDispatcher.
     *
     * @param array|\Iterator $requests Requests to send in parallel
     * @param array           $options  Associative array of options
     *                                  - parallel: (int) Max number of requests to send in parallel
     *                                  - complete: Callable that receives a RequestAfterSendEvent
     *                                  - error: Callable that receives a RequestErrorEvent
     */
    public function sendAll($requests, array $options = []);

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL
     *
     * @return string|null
     */
    public function getBaseUrl();
}
