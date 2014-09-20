<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\Middleware;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\CurlMultiAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Core;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\RingFutureInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;

/**
 * HTTP /* Replaced /* Replaced /* Replaced client */ */ */
 */
class Client implements ClientInterface
{
    use HasEmitterTrait;

    /** @var MessageFactoryInterface Request factory used by the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $messageFactory;

    /** @var callable */
    private $adapter;

    /** @var Url Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $baseUrl;

    /** @var array Default request options */
    private $defaults;

    /** @var Fsm Request state machine */
    private $fsm;

    /**
     * Clients accept an array of constructor parameters.
     *
     * Here's an example of creating a /* Replaced /* Replaced /* Replaced client */ */ */ using an URI template for the
     * /* Replaced /* Replaced /* Replaced client */ */ */'s base_url and an array of default request options to apply
     * to each request:
     *
     *     $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
     *         'base_url' => [
     *              'http://www.foo.com/{version}/',
     *              ['version' => '123']
     *          ],
     *         'defaults' => [
     *             'timeout'         => 10,
     *             'allow_redirects' => false,
     *             'proxy'           => '192.168.16.1:10'
     *         ]
     *     ]);
     *
     * @param array $config Client configuration settings
     *     - base_url: Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ that is merged into relative URLs.
     *       Can be a string or an array that contains a URI template followed
     *       by an associative array of expansion variables to inject into the
     *       URI template.
     *     - adapter: callable adapter used to transfer requests
     *     - message_factory: Factory used to create request and response object
     *     - defaults: Default request options to apply to each request
     *     - emitter: Event emitter used for request events
     *     - fsm: (internal use only) The request finite state machine.
     */
    public function __construct(array $config = [])
    {
        $this->configureBaseUrl($config);
        $this->configureDefaults($config);
        if (isset($config['emitter'])) {
            $this->emitter = $config['emitter'];
        }
        $this->messageFactory = isset($config['message_factory'])
            ? $config['message_factory']
            : new MessageFactory();
        $this->adapter = isset($config['adapter'])
            ? $config['adapter']
            : self::getDefaultAdapter();
        $this->fsm = isset($config['fsm'])
            ? $config['fsm']
            : RequestEvents::createFsm();
    }

    /**
     * Create a default adapter to use based on the environment
     *
     * @throws \RuntimeException if no viable adapter is available.
     */
    public static function getDefaultAdapter()
    {
        $default = $future = $streaming = null;

        if (extension_loaded('curl')) {
            $config = [
                'select_timepout' => isset($_SERVER['GUZZLE_CURL_SELECT_TIMEOUT'])
                    ? $_SERVER['GUZZLE_CURL_SELECT_TIMEOUT'] : 1
            ];
            if (isset($_SERVER['GUZZLE_CURL_MAX_HANDLES'])) {
                $config['max_handles'] = $_SERVER['GUZZLE_CURL_MAX_HANDLES'];
            }
            $future = new CurlMultiAdapter($config);
            if (function_exists('curl_reset')) {
                $default = new CurlAdapter();
            }
        }

        if (ini_get('allow_url_fopen')) {
            $streaming = new StreamAdapter();
        }

        if (!($default = ($default ?: $future) ?: $streaming)) {
            throw new \RuntimeException('/* Replaced /* Replaced /* Replaced Guzzle */ */ */ requires cURL, the '
                . 'allow_url_fopen ini setting, or a custom HTTP adapter.');
        }

        $handler = $default;

        if ($streaming && $streaming !== $default) {
            $handler = Middleware::wrapStreaming($default, $streaming);
        }

        if ($future) {
            $handler = Middleware::wrapFuture($handler, $future);
        }

        return $handler;
    }

    /**
     * Get the default User-Agent string to use with /* Replaced /* Replaced /* Replaced Guzzle */ */ */
     *
     * @return string
     */
    public static function getDefaultUserAgent()
    {
        static $defaultAgent = '';
        if (!$defaultAgent) {
            $defaultAgent = '/* Replaced /* Replaced /* Replaced Guzzle */ */ *//' . self::VERSION;
            if (extension_loaded('curl')) {
                $defaultAgent .= ' curl/' . curl_version()['version'];
            }
            $defaultAgent .= ' PHP/' . PHP_VERSION;
        }

        return $defaultAgent;
    }

    public function getDefaultOption($keyOrPath = null)
    {
        return $keyOrPath === null
            ? $this->defaults
            : Utils::getPath($this->defaults, $keyOrPath);
    }

    public function setDefaultOption($keyOrPath, $value)
    {
        Utils::setPath($this->defaults, $keyOrPath, $value);
    }

    public function getBaseUrl()
    {
        return (string) $this->baseUrl;
    }

    public function createRequest($method, $url = null, array $options = [])
    {
        $headers = $this->mergeDefaults($options);
        // Use a clone of the /* Replaced /* Replaced /* Replaced client */ */ */'s emitter
        $options['config']['emitter'] = clone $this->getEmitter();

        $request = $this->messageFactory->createRequest(
            $method,
            $url ? (string) $this->buildUrl($url) : (string) $this->baseUrl,
            $options
        );

        // Merge in default headers
        if ($headers) {
            foreach ($headers as $key => $value) {
                if (!$request->hasHeader($key)) {
                    $request->setHeader($key, $value);
                }
            }
        }

        return $request;
    }

    public function get($url = null, $options = [])
    {
        return $this->send($this->createRequest('GET', $url, $options));
    }

    public function head($url = null, array $options = [])
    {
        return $this->send($this->createRequest('HEAD', $url, $options));
    }

    public function delete($url = null, array $options = [])
    {
        return $this->send($this->createRequest('DELETE', $url, $options));
    }

    public function put($url = null, array $options = [])
    {
        return $this->send($this->createRequest('PUT', $url, $options));
    }

    public function patch($url = null, array $options = [])
    {
        return $this->send($this->createRequest('PATCH', $url, $options));
    }

    public function post($url = null, array $options = [])
    {
        return $this->send($this->createRequest('POST', $url, $options));
    }

    public function options($url = null, array $options = [])
    {
        return $this->send($this->createRequest('OPTIONS', $url, $options));
    }

    public function send(RequestInterface $request)
    {
        $trans = new Transaction($this, $request);
        $this->fsm->run($trans, 'send');

        // Return a response if one was set during the before event.
        if ($trans->response) {
            $trans->state = 'complete';
            $this->fsm->run($trans);
            return $trans->response;
        }

        // Send the request using the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring handler
        $adapter = $this->adapter;
        $response = $adapter(
            RingBridge::prepareRingRequest(
                $trans, $this->messageFactory, $this->fsm
            )
        );

        // Future responses do not need to process right away.
        if ($response instanceof RingFutureInterface) {
            $trans->response = $this->createFutureResponse($trans, $response);
            return $trans->response;
        }

        // Throw a wrapped exception if the transactions has an error.
        if ($trans->exception) {
            throw $this->wrapException($trans->request, $trans->exception);
        }

        // Return a response if one was synchronously available.
        if ($trans->response) {
            return $trans->response;
        }

        throw $this->getNoRingResponseException($trans->request);
    }

    private function wrapException(RequestInterface $request, \Exception $e)
    {
        return $e instanceof RequestException
            ? $e
            : new RequestException($e->getMessage(), $request, null, $e);
    }

    private function createFutureResponse(
        Transaction $trans,
        RingFutureInterface $response
    ) {
        // Create a future response that's hooked up to the ring future.
        return new FutureResponse(
            // Dereference function
            function () use ($response, $trans) {
                // Dereference the underlying future and block until complete.
                $response->deref();
                // Exceptions need to be removed when intercepting errors,
                // otherwise, they're thrown.
                if ($trans->exception) {
                    throw $this->wrapException($trans->request, $trans->exception);
                }
                // No exception, so the transaction should have a response.
                if ($trans->response) {
                    return $trans->response;
                }
                throw $this->getNoRingResponseException($trans->request);
            },
            // Cancel function. Just proxy to the underlying future.
            function () use ($response) {
                return $response->cancel();
            }
        );
    }

    /**
     * Get an array of default options to apply to the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $settings = [
            'allow_redirects' => true,
            'exceptions'      => true,
            'decode_content'  => true,
            'verify'          => true
        ];

        // Use the standard Linux HTTP_PROXY and HTTPS_PROXY if set
        if (isset($_SERVER['HTTP_PROXY'])) {
            $settings['proxy']['http'] = $_SERVER['HTTP_PROXY'];
        }

        if (isset($_SERVER['HTTPS_PROXY'])) {
            $settings['proxy']['https'] = $_SERVER['HTTPS_PROXY'];
        }

        return $settings;
    }

    /**
     * Expand a URI template and inherit from the base URL if it's relative
     *
     * @param string|array $url URL or URI template to expand
     *
     * @return string
     */
    private function buildUrl($url)
    {
        if (!is_array($url)) {
            if (strpos($url, '://')) {
                return (string) $url;
            }
            return (string) $this->baseUrl->combine($url);
        } elseif (strpos($url[0], '://')) {
            return Utils::uriTemplate($url[0], $url[1]);
        }

        return (string) $this->baseUrl->combine(
            Utils::uriTemplate($url[0], $url[1])
        );
    }

    private function configureBaseUrl(&$config)
    {
        if (!isset($config['base_url'])) {
            $this->baseUrl = new Url('', '');
        } elseif (is_array($config['base_url'])) {
            $this->baseUrl = Url::fromString(
                Utils::uriTemplate(
                    $config['base_url'][0],
                    $config['base_url'][1]
                )
            );
            $config['base_url'] = (string) $this->baseUrl;
        } else {
            $this->baseUrl = Url::fromString($config['base_url']);
        }
    }

    private function configureDefaults($config)
    {
        if (!isset($config['defaults'])) {
            $this->defaults = $this->getDefaultOptions();
        } else {
            $this->defaults = array_replace(
                $this->getDefaultOptions(),
                $config['defaults']
            );
        }

        // Add the default user-agent header
        if (!isset($this->defaults['headers'])) {
            $this->defaults['headers'] = [
                'User-Agent' => static::getDefaultUserAgent()
            ];
        } elseif (!Core::hasHeader($this->defaults, 'User-Agent')) {
            // Add the User-Agent header if one was not already set
            $this->defaults['headers']['User-Agent'] = static::getDefaultUserAgent();
        }
    }

    /**
     * Merges default options into the array passed by reference and returns
     * an array of headers that need to be merged in after the request is
     * created.
     *
     * @param array $options Options to modify by reference
     *
     * @return array|null
     */
    private function mergeDefaults(&$options)
    {
        // Merging optimization for when no headers are present
        if (!isset($options['headers']) || !isset($this->defaults['headers'])) {
            $options = array_replace_recursive($this->defaults, $options);
            return null;
        }

        $defaults = $this->defaults;
        unset($defaults['headers']);
        $options = array_replace_recursive($defaults, $options);

        return $this->defaults['headers'];
    }

    private function getNoRingResponseException(RequestInterface $request)
    {
        return new RequestException(
            'Sending the request did not return a response, exception, or '
            . 'populate the transaction with a response. This is most likely '
            . 'due to an incorrectly implemented /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Ring adapter.',
            $request
        );
    }

    /**
     * @deprecated Use {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool} instead.
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool
     */
    public function sendAll($requests, array $options = [])
    {
        Pool::send($this, $requests, $options);
    }
}
