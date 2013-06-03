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

    /** @var string RFC 1123 HTTP-Date */
    const HTTP_DATE = 'D, d M Y H:i:s \G\M\T';

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
     * Set SSL verification options.
     *
     * Setting $certificateAuthority to TRUE will result in the bundled
     * cacert.pem being used to verify against the remote host.
     *
     * Alternate certificates to verify against can be specified with the
     * $certificateAuthority option set to a certificate file location to be
     * used with CURLOPT_CAINFO, or a certificate directory path to be used
     * with the CURLOPT_CAPATH option.
     *
     * Setting $certificateAuthority to FALSE will turn off peer verification,
     * unset the bundled cacert.pem, and disable host verification. Please
     * don't do this unless you really know what you're doing, and why
     * you're doing it.
     *
     * @param string|bool $certificateAuthority bool, file path, or directory path
     * @param bool        $verifyPeer           FALSE to stop cURL from verifying the peer's certificate.
     * @param int         $verifyHost           Set the cURL handle's CURLOPT_SSL_VERIFYHOST option
     *
     * @return ClientInterface
     */
    public function setSslVerification($certificateAuthority = true, $verifyPeer = true, $verifyHost = 2);

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
     * @param array                                     $options Array of options to apply to the request
     *
     * @return RequestInterface
     * @throws InvalidArgumentException if a URI array is passed that does not contain exactly two elements: the URI
     *                                  followed by template variables
     */
    public function createRequest(
        $method = RequestInterface::GET,
        $uri = null,
        $headers = null,
        $body = null,
        array $options = array()
    );

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
     * Set the User-Agent header to be used on all requests from the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $userAgent      User agent string
     * @param bool   $includeDefault Set to true to prepend the value to /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s default user agent string
     *
     * @return self
     */
    public function setUserAgent($userAgent, $includeDefault = false);

    /**
     * Create a GET request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array     $uri     Resource URI
     * @param array|Collection $headers HTTP headers
     * @param array            $options Options to apply to the request. For BC compatibility, you can also pass a
     *                                  string to tell /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to download the body of the response to a particular
     *                                  location. Use the 'body' option instead for forward compatibility.
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function get($uri = null, $headers = null, $options = array());

    /**
     * Create a HEAD request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array     $uri     Resource URI
     * @param array|Collection $headers HTTP headers
     * @param array            $options Options to apply to the request
     *
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function head($uri = null, $headers = null, array $options = array());

    /**
     * Create a DELETE request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                        $uri     Resource URI
     * @param array|Collection                    $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     * @param array                               $options Options to apply to the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function delete($uri = null, $headers = null, $body = null, array $options = array());

    /**
     * Create a PUT request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                        $uri     Resource URI
     * @param array|Collection                    $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     * @param array                               $options Options to apply to the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function put($uri = null, $headers = null, $body = null, array $options = array());

    /**
     * Create a PATCH request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                        $uri     Resource URI
     * @param array|Collection                    $headers HTTP headers
     * @param string|resource|EntityBodyInterface $body    Body to send in the request
     * @param array                               $options Options to apply to the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function patch($uri = null, $headers = null, $body = null, array $options = array());

    /**
     * Create a POST request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array                                $uri      Resource URI
     * @param array|Collection                            $headers  HTTP headers
     * @param array|Collection|string|EntityBodyInterface $postBody POST body. Can be a string, EntityBody, or
     *                                                    associative array of POST fields to send in the body of the
     *                                                    request.  Prefix a value in the array with the @ symbol to
     *                                                    reference a file.
     * @param array                                       $options Options to apply to the request
     *
     * @return EntityEnclosingRequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function post($uri = null, $headers = null, $postBody = null, array $options = array());

    /**
     * Create an OPTIONS request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string|array $uri     Resource URI
     * @param array        $options Options to apply to the request
     *
     * @return RequestInterface
     * @see    /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface::createRequest()
     */
    public function options($uri = null, array $options = array());

    /**
     * Sends a single request or an array of requests in parallel
     *
     * @param array|RequestInterface $requests One or more RequestInterface objects to send
     *
     * @return Response|array Returns a single Response or an array of Response objects
     */
    public function send($requests);

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
