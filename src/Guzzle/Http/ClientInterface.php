<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\Url;

/**
 * Client interface for sending HTTP requests
 */
interface ClientInterface extends HasDispatcherInterface
{
    /**
     * Create and return a new {@see RequestInterface} configured for the /* Replaced /* Replaced /* Replaced client */ */ */.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a relative path to append to the base path of
     * the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can contain the query string as well. Use an array to provide a URL template and additional
     * variables to use in the URL template expansion.
     *
     * @param string                          $method  HTTP method
     * @param string|array                    $url     Resource URL
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Array of options to apply to the request
     *
     * @return RequestInterface
     * @throws InvalidArgumentException if a URL array is passed that does not contain exactly two elements: the URL
     *                                  followed by template variables
     */
    public function createRequest($method, $url = null, $body = null, array $options = []);

    /**
     * Send a GET request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function get($url = null, $options = []);

    /**
     * Send a HEAD request
     *
     * @param string|array|Url $url     Absolute or relative URL
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function head($url = null, array $options = []);

    /**
     * Send a DELETE request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function delete($url = null, array $options = []);

    /**
     * Send a PUT request
     *
     * @param string|array|Url                $url     Resource URL
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function put($url = null, $body = null, array $options = []);

    /**
     * Send a PATCH request
     *
     * @param string|array|Url                $url     Resource URL
     * @param string|StreamInterface|resource $body    Body to send
     * @param array                           $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function patch($url = null, $body = null, array $options = []);

    /**
     * Send an OPTIONS request
     *
     * @param string|array|Url $url     Resource URL
     * @param array            $options Options to apply to the request
     *
     * @return ResponseInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function options($url = null, array $options = []);

    /**
     * Sends a single request
     *
     * @param RequestInterface $request Request to send
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request);

    /**
     * Send one or more requests in parallel
     *
     * @param \Traversable RequestInterface objects to send
     *
     * @return Transaction
     */
    public function batch($requests);

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL
     *
     * @return string|null
     */
    public function getBaseUrl();

    /**
     * Set the User-Agent header to be used on all requests sent from the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $userAgent      User agent string
     * @param bool   $includeDefault Set to true to prepend the value to /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s default user agent string
     *
     * @return self
     */
    public function setUserAgent($userAgent, $includeDefault = false);

    /**
     * Set a default request option on the /* Replaced /* Replaced /* Replaced client */ */ */ that will be used as a default for each request
     *
     * @param string $keyOrPath request.options key (e.g. allow_redirects) or path to a nested key (e.g. headers/foo)
     * @param mixed  $value     Value to set
     *
     * @return self
     */
    public function setDefaultOption($keyOrPath, $value);
}
