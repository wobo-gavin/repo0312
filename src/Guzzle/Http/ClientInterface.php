<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\UriTemplate\UriTemplateInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMultiInterface;

/**
 * Client interface for send HTTP requests
 */
interface ClientInterface extends HasDispatcherInterface
{
    const CREATE_REQUEST = '/* Replaced /* Replaced /* Replaced client */ */ */.create_request';

    /**
     * Set the configuration object to use with the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param array|Collection|string $config Parameters that define how the /* Replaced /* Replaced /* Replaced client */ */ */ behaves and connects to a
     *                                        webservice. Pass an array or a Collection object.
     * @return ClientInterface
     */
    public function setConfig($config);

    /**
     * Get a configuration setting or all of the configuration settings
     *
     * @param bool|string $key Configuration value to retrieve.  Set to FALSE to retrieve all values of the /* Replaced /* Replaced /* Replaced client */ */ */.
     *                         The object return can be modified, and modifications will affect the /* Replaced /* Replaced /* Replaced client */ */ */'s config.
     * @return mixed|Collection
     */
    public function getConfig($key = false);

    /**
     * Get the default HTTP headers to add to each request created by the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Collection
     */
    public function getDefaultHeaders();

    /**
     * Set the default HTTP headers to add to each request created by the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param array|Collection $headers Default HTTP headers
     *
     * @return ClientInterface
     */
    public function setDefaultHeaders($headers);

    /**
     * Set the URI template expander to use with the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param UriTemplateInterface $uriTemplate URI template expander
     *
     * @return ClientInterface
     */
    public function setUriTemplate(UriTemplateInterface $uriTemplate);

    /**
     * Get the URI template expander used by the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return UriTemplateInterface
     */
    public function getUriTemplate();

    /**
     * Expand a URI template using /* Replaced /* Replaced /* Replaced client */ */ */ configuration data
     *
     * @param string $template  URI template to expand
     * @param array  $variables Additional variables to use in the expansion
     *
     * @return string
     */
    public function expandTemplate($template, array $variables = null);

    /**
     * Create and return a new {@see RequestInterface} configured for the /* Replaced /* Replaced /* Replaced client */ */ */.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a relative path to append to the base path of
     * the /* Replaced /* Replaced /* Replaced client */ */ */. The URI can contain the query string as well.  Use an array to provide a URI template and additional
     * variables to use in the URI template expansion.
     *
     * @param string                                    $method  HTTP method.  Defaults to GET
     * @param string|array                              $uri     Resource URI.
     * @param array|Collection                          $headers HTTP headers
     * @param string|resource|array|EntityBodyInterface $body    Entity body of request (POST/PUT) or response (GET)
     *
     * @return RequestInterface
     * @throws InvalidArgumentException if a URI array is passed that does not contain exactly two elements: the URI
     *                                  followed by template variables
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null);

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL as either an expanded or raw URI template
     *
     * @param bool $expand Set to FALSE to get the raw base URL without URI template expansion
     *
     * @return string|null
     */
    public function getBaseUrl($expand = true);

    /**
     * Set the base URL of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $url The base service endpoint URL of the webservice
     *
     * @return ClientInterface
     */
    public function setBaseUrl($url);

    /**
     * Set the name of your application and application version that will be
     * appended to the User-Agent header of all requests.
     *
     * @param string $userAgent      User agent string
     * @param bool   $includeDefault Set to TRUE to append the default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ use agent
     *
     * @return ClientInterface
     */
    public function setUserAgent($userAgent, $includeDefault = false);

    /**
     * Create a GET request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                              $uri     Resource URI
     * @param array|Collection                          $headers HTTP headers
     * @param string|resource|array|EntityBodyInterface $body    Where to store the response entity body
     *
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function get($uri = null, $headers = null, $body = null);

    /**
     * Create a HEAD request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array     $uri     Resource URI
     * @param array|Collection $headers HTTP headers
     *
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function head($uri = null, $headers = null);

    /**
     * Create a DELETE request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array     $uri     Resource URI
     * @param array|Collection $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function delete($uri = null, $headers = null, $body = null);

    /**
     * Create a PUT request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                        $uri     Resource URI
     * @param array|Collection                    $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function put($uri = null, $headers = null, $body = null);

    /**
     * Create a PATCH request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                        $uri     Resource URI
     * @param array|Collection                    $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function patch($uri = null, $headers = null, $body = null);

    /**
     * Create a POST request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                                $uri      Resource URI
     * @param array|Collection                            $headers  HTTP headers
     * @param array|Collection|string|EntityBodyInterface $postBody POST body. Can be a string, EntityBody, or
     *                                                    associative array of POST fields to send in the body of the
     *                                                    request.  Prefix a value in the array with the @ symbol to
     *                                                    reference a file.
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function post($uri = null, $headers = null, $postBody = null);

    /**
     * Create an OPTIONS request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array $uri Resource URI
     *
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function options($uri = null);

    /**
     * Sends a single request or an array of requests in parallel
     *
     * @param array $requests Request(s) to send
     *
     * @return array Returns the response(s)
     */
    public function send($requests);

    /**
     * Set a curl multi object to be used internally by the /* Replaced /* Replaced /* Replaced client */ */ */ for transferring requests.
     *
     * @param CurlMultiInterface $curlMulti Multi object
     *
     * @return ClientInterface
     */
    public function setCurlMulti(CurlMultiInterface $curlMulti);

    /**
     * Get the curl multi object to be used internally by the /* Replaced /* Replaced /* Replaced client */ */ */ for transferring requests.
     *
     * @return CurlMultiInterface
     */
    public function getCurlMulti();

    /**
     * Set the request factory to use with the /* Replaced /* Replaced /* Replaced client */ */ */ when creating requests
     *
     * @param RequestFactoryInterface $factory Request factory
     *
     * @return ClientInterface
     */
    public function setRequestFactory(RequestFactoryInterface $factory);
}
