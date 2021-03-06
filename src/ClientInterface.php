<?php

namespace /* Replaced Guzzle */Http;

use /* Replaced Guzzle */Http\Exception\/* Replaced Guzzle */Exception;
use /* Replaced Guzzle */Http\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Client interface for sending HTTP requests.
 */
interface ClientInterface
{
    /**
     * The /* Replaced Guzzle */ major version.
     */
    const MAJOR_VERSION = 7;

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @throws /* Replaced Guzzle */Exception
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface;

    /**
     * Asynchronously send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface;

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the /* Replaced client */, or a
     * relative path to append to the base path of the /* Replaced client */. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @throws /* Replaced Guzzle */Exception
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface;

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the /* Replaced client */, or a
     * relative path to append to the base path of the /* Replaced client */. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface;

    /**
     * Get a /* Replaced client */ configuration option.
     *
     * These options include default request options of the /* Replaced client */, a "handler"
     * (if utilized by the concrete /* Replaced client */), and a "base_uri" if utilized by
     * the concrete /* Replaced client */.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     *
     * @deprecated ClientInterface::getConfig will be removed in /* Replaced guzzle */http//* Replaced guzzle */:8.0.
     */
    public function getConfig(?string $option = null);
}
