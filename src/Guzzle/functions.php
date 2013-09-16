<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

/**
 * Send a custom request
 *
 * @param string $method  HTTP request method (GET, POST, HEAD, DELETE, PUT, etc)
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param mixed  $body    Request body
 * @param array  $options Options to use with the request. See: /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::applyOptions()
 *
 * @return ResponseInterface
 */
function request($method, $url, array $headers = [], $body = null, $options = [])
{
    static $/* Replaced /* Replaced /* Replaced client */ */ */;
    if (!$/* Replaced /* Replaced /* Replaced client */ */ */) {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
    }

    return $/* Replaced /* Replaced /* Replaced client */ */ */->send($/* Replaced /* Replaced /* Replaced client */ */ */->createRequest($method, $url, $headers, $body, $options));
}

/**
 * Send a GET request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function get($url, array $headers = [], $options = [])
{
    return request('GET', $url, $headers, null, $options);
}

/**
 * Send a HEAD request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function head($url, array $headers = [], $options = [])
{
    return request('HEAD', $url, $headers, null, $options);
}

/**
 * Send a DELETE request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function delete($url, array $headers = [], $options = [])
{
    return request('DELETE', $url, $headers, null, $options);
}

/**
 * Send a POST request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param mixed  $body    Body to send
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function post($url, array $headers = [], $body = null, $options = [])
{
    return request('POST', $url, $headers, $body, $options);
}

/**
 * Send a PUT request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param mixed  $body    Body to send
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function put($url, array $headers = [], $body = null, $options = [])
{
    return request('PUT', $url, $headers, $body, $options);
}

/**
 * Send a PATCH request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param mixed  $body    Body to send
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function patch($url, array $headers = [], $body = null, $options = [])
{
    return request('PATCH', $url, $headers, $body, $options);
}

/**
 * Send an OPTIONS request
 *
 * @param string $url     URL of the request
 * @param array  $headers HTTP headers
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function options($url, array $headers = [], $options = [])
{
    return request('OPTIONS', $url, $headers, $options);
}
