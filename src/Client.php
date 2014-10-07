<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\CancelledFutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\Middleware;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\CurlMultiAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Core;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Future\FutureInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Exception\CancelledException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use React\Promise\FulfilledPromise;

/**
 * HTTP /* Replaced /* Replaced /* Replaced client */ */ */
 */
class Client implements ClientInterface
{
    use HasEmitterTrait;

    /** @var MessageFactoryInterface Request factory used by the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $messageFactory;

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
        $adapter = isset($config['adapter'])
            ? $config['adapter']
            : self::getDefaultAdapter();
        $this->fsm = isset($config['fsm'])
            ? $config['fsm']
            : $this->createDefaultFsm($adapter, $this->messageFactory);
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
                'select_timeout' => getenv('GUZZLE_CURL_SELECT_TIMEOUT') ?: 1
            ];
            if ($maxHandles = getenv('GUZZLE_CURL_MAX_HANDLES')) {
                $config['max_handles'] = $maxHandles;
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

        // Ensure a future response is returned if one was requested.
        if ($request->getConfig()->get('future')) {
            try {
                $this->fsm->run($trans);
                // Turn the normal response into a future if needed.
                return $trans->response instanceof FutureInterface
                    ? $trans->response
                    : new FutureResponse(new FulfilledPromise($trans->response));
            } catch (RequestException $e) {
                // Wrap the exception in a promise if the user asked for a future.
                return CancelledFutureResponse::fromException($e);
            }
        } else {
            try {
                $this->fsm->run($trans);
                // When a FutureResponse is here, then it was created by the
                // FSM and there isn't a way to cancel it in an event because
                // the future is returned immediately and the events run after
                // the future is created, modifying the result of the future.
                // A future can be cancelled with a CancelledFutureResponse
                // intercept of the "end" event. In that case, we deref, catch
                // the exception, and return the original future that is now
                // marked as cancelled. If a non-future is found, then a
                // response was injected in a "before" event of an emitter
                // before the FSM created the future.
                return $trans->response instanceof FutureInterface
                    ? $trans->response->deref()
                    : $trans->response;
            } catch (CancelledException $e) {
                // Cancelled exceptions can be encountered
                return $trans->response;
            } catch (\Exception $e) {
                throw RequestException::wrapException($trans->request, $e);
            }
        }
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
        if ($proxy = getenv('HTTP_PROXY')) {
            $settings['proxy']['http'] = $proxy;
        }

        if ($proxy = getenv('HTTPS_PROXY')) {
            $settings['proxy']['https'] = $proxy;
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
        // URI template (absolute or relative)
        if (!is_array($url)) {
            return strpos($url, '://')
                ? (string) $url
                : (string) $this->baseUrl->combine($url);
        }

        // Absolute URL
        if (strpos($url[0], '://')) {
            return Utils::uriTemplate($url[0], $url[1]);
        }

        // Combine the relative URL with the base URL
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

    private function createDefaultFsm(
        callable $adapter,
        MessageFactoryInterface $mf
    ) {
        return new RequestFsm(function (Transaction $t) use ($adapter, $mf) {
            $t->response = FutureResponse::proxy(
                $adapter(RingBridge::prepareRingRequest($t)),
                function ($value) use ($t) {
                    RingBridge::completeRingResponse(
                        $t, $value, $this->messageFactory, $this->fsm
                    );
                    if ($t->exception) {
                        throw RequestException::wrapException($t->request, $t->exception);
                    }
                    return $t->response;
                }
            );
        });
    }

    /**
     * @deprecated Use {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool} instead.
     * @see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool
     */
    public function sendAll($requests, array $options = [])
    {
        (new Pool($this, $requests, $options))->deref();
    }
}
