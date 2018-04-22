<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Client interface for sending HTTP requests.
 */
interface ClientInterface
{
    const VERSION = '6.3.3';

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return ResponseInterface
     * @throws /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
     */
    public function send(RequestInterface $request, array $options = []);

    /**
     * Asynchronously send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return PromiseInterface
     */
    public function sendAsync(RequestInterface $request, array $options = []);

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a
     * relative path to append to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return ResponseInterface
     * @throws /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
     */
    public function request($method, $uri, array $options = []);

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a
     * relative path to append to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function requestAsync($method, $uri, array $options = []);

    /**
     * Get a /* Replaced /* Replaced /* Replaced client */ */ */ configuration option.
     *
     * These options include default request options of the /* Replaced /* Replaced /* Replaced client */ */ */, a "handler"
     * (if utilized by the concrete /* Replaced /* Replaced /* Replaced client */ */ */), and a "base_uri" if utilized by
     * the concrete /* Replaced /* Replaced /* Replaced client */ */ */.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getConfig($option = null);
}
