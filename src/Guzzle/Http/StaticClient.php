<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamRequestFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory;

/**
 * Simplified interface to /* Replaced /* Replaced /* Replaced Guzzle */ */ */ that does not require a class to be instantiated
 */
final class StaticClient
{
    /** @var Client /* Replaced /* Replaced /* Replaced Guzzle */ */ */ /* Replaced /* Replaced /* Replaced client */ */ */ */
    private static $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * Mount the /* Replaced /* Replaced /* Replaced client */ */ */ to a simpler class name for a specific /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string          $className Class name to use to mount
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */    Client used to send requests
     */
    public static function mount($className = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */', ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */ = null)
    {
        class_alias(__CLASS__, $className);
        if ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            self::$/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        }
    }

    /**
     * @param  string $method  HTTP request method (GET, POST, HEAD, DELETE, PUT, etc)
     * @param  string $url     URL of the request
     * @param  array  $options Options to use with the request. See: /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::applyOptions()
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response|\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream
     */
    public static function request($method, $url, $options = array())
    {
        // @codeCoverageIgnoreStart
        if (!self::$/* Replaced /* Replaced /* Replaced client */ */ */) {
            self::$/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        }
        // @codeCoverageIgnoreEnd

        $request = self::$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest($method, $url, null, null, $options);

        if (isset($options['stream'])) {
            if ($options['stream'] instanceof StreamRequestFactoryInterface) {
                return $options['stream']->fromRequest($request);
            } elseif ($options['stream'] == true) {
                $streamFactory = new PhpStreamRequestFactory();
                return $streamFactory->fromRequest($request);
            }
        }

        return $request->send();
    }

    /**
     * Send a GET request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function get($url, $options = array())
    {
        return self::request('GET', $url, $options);
    }

    /**
     * Send a HEAD request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function head($url, $options = array())
    {
        return self::request('HEAD', $url, $options);
    }

    /**
     * Send a DELETE request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function delete($url, $options = array())
    {
        return self::request('DELETE', $url, $options);
    }

    /**
     * Send a POST request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function post($url, $options = array())
    {
        return self::request('POST', $url, $options);
    }

    /**
     * Send a PUT request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function put($url, $options = array())
    {
        return self::request('PUT', $url, $options);
    }

    /**
     * Send a PATCH request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function patch($url, $options = array())
    {
        return self::request('PATCH', $url, $options);
    }

    /**
     * Send an OPTIONS request
     *
     * @param string $url     URL of the request
     * @param array  $options Array of request options
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */::request for a list of available options
     */
    public static function options($url, $options = array())
    {
        return self::request('OPTIONS', $url, $options);
    }
}
