<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\InvalidRequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @method ResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface head(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface delete(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface getAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface headAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface putAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface postAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface patchAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface deleteAsync(string|UriInterface $uri, array $options = [])
 */
class Client implements ClientInterface, \Psr\Http\Client\ClientInterface
{
    /** @var array Default request options */
    private $config;

    /**
     * Clients accept an array of constructor parameters.
     *
     * Here's an example of creating a /* Replaced /* Replaced /* Replaced client */ */ */ using a base_uri and an array of
     * default request options to apply to each request:
     *
     *     $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
     *         'base_uri'        => 'http://www.foo.com/1.0/',
     *         'timeout'         => 0,
     *         'allow_redirects' => false,
     *         'proxy'           => '192.168.16.1:10'
     *     ]);
     *
     * Client configuration settings include the following options:
     *
     * - handler: (callable) Function that transfers HTTP requests over the
     *   wire. The function is called with a /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Http\Message\RequestInterface
     *   and array of transfer options, and must return a
     *   /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface that is fulfilled with a
     *   /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Http\Message\ResponseInterface on success. "handler" is a
     *   constructor only option that cannot be overridden in per/request
     *   options. If no handler is provided, a default handler will be created
     *   that enables all of the request options below by attaching all of the
     *   default middleware to the handler.
     * - base_uri: (string|UriInterface) Base URI of the /* Replaced /* Replaced /* Replaced client */ */ */ that is merged
     *   into relative URIs. Can be a string or instance of UriInterface.
     * - **: any request option
     *
     * @param array $config Client configuration settings.
     *
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions for a list of available request options.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['handler'])) {
            $config['handler'] = HandlerStack::create();
        } elseif (!\is_callable($config['handler'])) {
            throw new \InvalidArgumentException('handler must be a callable');
        }

        // Convert the base_uri to a UriInterface
        if (isset($config['base_uri'])) {
            $config['base_uri'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\uri_for($config['base_uri']);
        }

        $this->configureDefaults($config);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return PromiseInterface|ResponseInterface
     */
    public function __call($method, $args)
    {
        if (\count($args) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array');
        }

        $uri = $args[0];
        $opts = isset($args[1]) ? $args[1] : [];

        return \substr($method, -5) === 'Async'
            ? $this->requestAsync(\substr($method, 0, -5), $uri, $opts)
            : $this->request($method, $uri, $opts);
    }

    /**
     * Asynchronously send an HTTP request.
     *
     * @param array $options Request options to apply to the given
     *                       request and to the transfer. See \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions.
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        // Merge the base URI into the request URI if needed.
        $options = $this->prepareDefaults($options);

        return $this->transfer(
            $request->withUri($this->buildUri($request->getUri(), $options), $request->hasHeader('Host')),
            $options
        );
    }

    /**
     * Send an HTTP request.
     *
     * @param array $options Request options to apply to the given
     *                       request and to the transfer. See \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions.
     *
     * @throws /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $options[RequestOptions::SYNCHRONOUS] = true;
        return $this->sendAsync($request, $options)->wait();
    }

    /**
     * The HttpClient PSR (PSR-18) specify this method.
     *
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $options[RequestOptions::SYNCHRONOUS] = true;
        $options[RequestOptions::ALLOW_REDIRECTS] = false;
        $options[RequestOptions::HTTP_ERRORS] = false;

        return $this->sendAsync($request, $options)->wait();
    }

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
     * @param array               $options Request options to apply. See \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions.
     */
    public function requestAsync(string $method, $uri = '', array $options = []): PromiseInterface
    {
        $options = $this->prepareDefaults($options);
        // Remove request modifying parameter because it can be done up-front.
        $headers = isset($options['headers']) ? $options['headers'] : [];
        $body = isset($options['body']) ? $options['body'] : null;
        $version = isset($options['version']) ? $options['version'] : '1.1';
        // Merge the URI into the base URI.
        $uri = $this->buildUri($uri, $options);
        if (\is_array($body)) {
            $this->invalidBody();
        }
        $request = new /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request($method, $uri, $headers, $body, $version);
        // Remove the option so that they are not doubly-applied.
        unset($options['headers'], $options['body'], $options['version']);

        return $this->transfer($request, $options);
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a
     * relative path to append to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply. See \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions.
     *
     * @throws /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
     */
    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        $options[RequestOptions::SYNCHRONOUS] = true;
        return $this->requestAsync($method, $uri, $options)->wait();
    }

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
    public function getConfig(?string $option = null)
    {
        return $option === null
            ? $this->config
            : (isset($this->config[$option]) ? $this->config[$option] : null);
    }

    /**
     * @param string|UriInterface|null $uri
     */
    private function buildUri($uri, array $config): UriInterface
    {
        // for BC we accept null which would otherwise fail in uri_for
        $uri = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\uri_for($uri === null ? '' : $uri);

        if (isset($config['base_uri'])) {
            $uri = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\UriResolver::resolve(/* Replaced /* Replaced /* Replaced Psr7 */ */ */\uri_for($config['base_uri']), $uri);
        }

        if (isset($config['idn_conversion']) && ($config['idn_conversion'] !== false)) {
            $idnOptions = ($config['idn_conversion'] === true) ? IDNA_DEFAULT : $config['idn_conversion'];
            $uri = _idn_uri_convert($uri, $idnOptions);
        }

        return $uri->getScheme() === '' && $uri->getHost() !== '' ? $uri->withScheme('http') : $uri;
    }

    /**
     * Configures the default options for a /* Replaced /* Replaced /* Replaced client */ */ */.
     */
    private function configureDefaults(array $config): void
    {
        $defaults = [
            'allow_redirects' => RedirectMiddleware::$defaultSettings,
            'http_errors'     => true,
            'decode_content'  => true,
            'verify'          => true,
            'cookies'         => false
        ];

        // idn_to_ascii() is a part of ext-intl and might be not available
        // Old ICU versions don't have this constant, so we are basically stuck (see https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ *//pull/2424
        // and https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ *//issues/2448 for details)
        $defaults['idn_conversion'] = \function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46');

        // Use the standard Linux HTTP_PROXY and HTTPS_PROXY if set.

        // We can only trust the HTTP_PROXY environment variable in a CLI
        // process due to the fact that PHP has no reliable mechanism to
        // get environment variables that start with "HTTP_".
        if (PHP_SAPI === 'cli' && \getenv('HTTP_PROXY')) {
            $defaults['proxy']['http'] = \getenv('HTTP_PROXY');
        }

        if ($proxy = \getenv('HTTPS_PROXY')) {
            $defaults['proxy']['https'] = $proxy;
        }

        if ($noProxy = \getenv('NO_PROXY')) {
            $cleanedNoProxy = \str_replace(' ', '', $noProxy);
            $defaults['proxy']['no'] = \explode(',', $cleanedNoProxy);
        }

        $this->config = $config + $defaults;

        if (!empty($config['cookies']) && $config['cookies'] === true) {
            $this->config['cookies'] = new CookieJar();
        }

        // Add the default user-agent header.
        if (!isset($this->config['headers'])) {
            $this->config['headers'] = ['User-Agent' => default_user_agent()];
        } else {
            // Add the User-Agent header if one was not already set.
            foreach (\array_keys($this->config['headers']) as $name) {
                if (\strtolower($name) === 'user-agent') {
                    return;
                }
            }
            $this->config['headers']['User-Agent'] = default_user_agent();
        }
    }

    /**
     * Merges default options into the array.
     *
     * @param array $options Options to modify by reference
     */
    private function prepareDefaults(array $options): array
    {
        $defaults = $this->config;

        if (!empty($defaults['headers'])) {
            // Default headers are only added if they are not present.
            $defaults['_conditional'] = $defaults['headers'];
            unset($defaults['headers']);
        }

        // Special handling for headers is required as they are added as
        // conditional headers and as headers passed to a request ctor.
        if (\array_key_exists('headers', $options)) {
            // Allows default headers to be unset.
            if ($options['headers'] === null) {
                $defaults['_conditional'] = [];
                unset($options['headers']);
            } elseif (!\is_array($options['headers'])) {
                throw new \InvalidArgumentException('headers must be an array');
            }
        }

        // Shallow merge defaults underneath options.
        $result = $options + $defaults;

        // Remove null values.
        foreach ($result as $k => $v) {
            if ($v === null) {
                unset($result[$k]);
            }
        }

        return $result;
    }

    /**
     * Transfers the given request and applies request options.
     *
     * The URI of the request is not modified and the request options are used
     * as-is without merging in default options.
     *
     * @param array $options See \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions.
     */
    private function transfer(RequestInterface $request, array $options): PromiseInterface
    {
        $request = $this->applyOptions($request, $options);
        /** @var HandlerStack $handler */
        $handler = $options['handler'];

        try {
            return Promise\promise_for($handler($request, $options));
        } catch (\Exception $e) {
            return Promise\rejection_for($e);
        }
    }

    /**
     * Applies the array of request options to a request.
     */
    private function applyOptions(RequestInterface $request, array &$options): RequestInterface
    {
        $modify = [
            'set_headers' => [],
        ];

        if (isset($options['headers'])) {
            $modify['set_headers'] = $options['headers'];
            unset($options['headers']);
        }

        if (isset($options['form_params'])) {
            if (isset($options['multipart'])) {
                throw new \InvalidArgumentException('You cannot use '
                    . 'form_params and multipart at the same time. Use the '
                    . 'form_params option if you want to send application/'
                    . 'x-www-form-urlencoded requests, and the multipart '
                    . 'option to send multipart/form-data requests.');
            }
            $options['body'] = \http_build_query($options['form_params'], '', '&');
            unset($options['form_params']);
            // Ensure that we don't have the header in different case and set the new value.
            $options['_conditional'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\_caseless_remove(['Content-Type'], $options['_conditional']);
            $options['_conditional']['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        if (isset($options['multipart'])) {
            $options['body'] = new /* Replaced /* Replaced /* Replaced Psr7 */ */ */\MultipartStream($options['multipart']);
            unset($options['multipart']);
        }

        if (isset($options['json'])) {
            $options['body'] = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_encode($options['json']);
            unset($options['json']);
            // Ensure that we don't have the header in different case and set the new value.
            $options['_conditional'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\_caseless_remove(['Content-Type'], $options['_conditional']);
            $options['_conditional']['Content-Type'] = 'application/json';
        }

        if (!empty($options['decode_content'])
            && $options['decode_content'] !== true
        ) {
            // Ensure that we don't have the header in different case and set the new value.
            $options['_conditional'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\_caseless_remove(['Accept-Encoding'], $options['_conditional']);
            $modify['set_headers']['Accept-Encoding'] = $options['decode_content'];
        }

        if (isset($options['body'])) {
            if (\is_array($options['body'])) {
                $this->invalidBody();
            }
            $modify['body'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for($options['body']);
            unset($options['body']);
        }

        if (!empty($options['auth']) && \is_array($options['auth'])) {
            $value = $options['auth'];
            $type = isset($value[2]) ? \strtolower($value[2]) : 'basic';
            switch ($type) {
                case 'basic':
                    // Ensure that we don't have the header in different case and set the new value.
                    $modify['set_headers'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\_caseless_remove(['Authorization'], $modify['set_headers']);
                    $modify['set_headers']['Authorization'] = 'Basic '
                        . \base64_encode("$value[0]:$value[1]");
                    break;
                case 'digest':
                    // @todo: Do not rely on curl
                    $options['curl'][CURLOPT_HTTPAUTH] = CURLAUTH_DIGEST;
                    $options['curl'][CURLOPT_USERPWD] = "$value[0]:$value[1]";
                    break;
                case 'ntlm':
                    $options['curl'][CURLOPT_HTTPAUTH] = CURLAUTH_NTLM;
                    $options['curl'][CURLOPT_USERPWD] = "$value[0]:$value[1]";
                    break;
            }
        }

        if (isset($options['query'])) {
            $value = $options['query'];
            if (\is_array($value)) {
                $value = \http_build_query($value, null, '&', PHP_QUERY_RFC3986);
            }
            if (!\is_string($value)) {
                throw new InvalidRequestException($request, 'query must be a string or array');
            }
            $modify['query'] = $value;
            unset($options['query']);
        }

        // Ensure that sink is not an invalid value.
        if (isset($options['sink'])) {
            // TODO: Add more sink validation?
            if (\is_bool($options['sink'])) {
                throw new InvalidRequestException($request, 'sink must not be a boolean');
            }
        }

        $request = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\modify_request($request, $modify);
        if ($request->getBody() instanceof /* Replaced /* Replaced /* Replaced Psr7 */ */ */\MultipartStream) {
            // Use a multipart/form-data POST if a Content-Type is not set.
            // Ensure that we don't have the header in different case and set the new value.
            $options['_conditional'] = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\_caseless_remove(['Content-Type'], $options['_conditional']);
            $options['_conditional']['Content-Type'] = 'multipart/form-data; boundary='
                . $request->getBody()->getBoundary();
        }

        // Merge in conditional headers if they are not present.
        if (isset($options['_conditional'])) {
            // Build up the changes so it's in a single clone of the message.
            $modify = [];
            foreach ($options['_conditional'] as $k => $v) {
                if (!$request->hasHeader($k)) {
                    $modify['set_headers'][$k] = $v;
                }
            }
            $request = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\modify_request($request, $modify);
            // Don't pass this internal value along to middleware/handlers.
            unset($options['_conditional']);
        }

        return $request;
    }

    /**
     * Throw Exception with pre-set message.
     *
     * @throws InvalidArgumentException Invalid body.
     */
    private function invalidBody(): void
    {
        throw new \InvalidArgumentException('Passing in the "body" request '
            . 'option as an array to send a POST request has been deprecated. '
            . 'Please use the "form_params" request option to send a '
            . 'application/x-www-form-urlencoded request, or the "multipart" '
            . 'request option to send a multipart/form-data request.');
    }
}
