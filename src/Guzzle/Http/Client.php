<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\FakeBatchAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Version;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\BatchAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\AdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamingProxyAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\Url;

/**
 * HTTP /* Replaced /* Replaced /* Replaced client */ */ */
 */
class Client implements ClientInterface
{
    use HasDispatcherTrait;

    /** @var MessageFactoryInterface Request factory used by the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $messageFactory;

    /** @var AdapterInterface */
    private $adapter;

    /** @var BatchAdapterInterface */
    private $batchAdapter;

    /** @var string Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $baseUrl;

    /** @var Collection Parameter object holding configuration data */
    private $config;

    /**
     * Clients accept an array of constructor parameters.
     *
     * Here's an example of creating a /* Replaced /* Replaced /* Replaced client */ */ */ using a URI template for the
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
     *                      - base_url: Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ that is merged into relative URLs. Can be a string or
     *                                  an array that contains a URI template followed by an associative array of
     *                                  expansion variables to inject into the URI template.
     *                      - adapter: Adapter used to transfer requests
     *                      - batch_adapter: Adapter used to transfer requests in parallel
     *                      - message_factory: Factory used to create request and response object
     *                      - defaults: Default request options to apply to each request
     */
    public function __construct(array $config = [])
    {
        // Add default request options
        if (!isset($config['defaults'])) {
            $config['defaults'] = $this->getDefaultOptions();
        } else {
            $config['defaults'] = array_replace($this->getDefaultOptions(), $config['defaults']);
        }

        $this->messageFactory = isset($config['message_factory']) ? $config['message_factory'] : new MessageFactory();
        $this->baseUrl = isset($config['base_url']) ? $this->buildUrl($config['base_url']) : '';
        $this->adapter = isset($config['adapter']) ? $config['adapter'] : $this->getDefaultAdapter();

        // If no batch adapter was explicitly provided and one was not defaulted
        // when creating the default adapter, then create one now
        if (isset($config['batch_adapter'])) {
            $this->batchAdapter = $config['batch_adapter'];
        } elseif (!$this->batchAdapter) {
            $this->batchAdapter = $this->getDefaultBatchAdapter();
        }

        // Add the default user-agent header
        if (!isset($config['defaults']['headers'])) {
            $config['defaults']['headers'] = ['User-Agent' => static::getDefaultUserAgent()];
        } elseif (!isset(array_change_key_case($config['defaults']['headers'])['user-agent'])) {
            // Add the User-Agent header if one was not already set
            $config['defaults']['headers']['User-Agent'] = static::getDefaultUserAgent();
        }

        $this->config = new Collection($config);
    }

    /**
     * Get the default User-Agent string to use with /* Replaced /* Replaced /* Replaced Guzzle */ */ */
     *
     * @return string
     */
    public static function getDefaultUserAgent()
    {
        return '/* Replaced /* Replaced /* Replaced Guzzle */ */ *//' . Version::VERSION . ' curl/' . curl_version()['version'] . ' PHP/' . PHP_VERSION;
    }

    public function getConfig($key)
    {
        return $this->config->getPath($key);
    }

    /**
     * Retrieve a default request option from the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $keyOrPath request.options key (e.g. allow_redirects) or path to a nested key (e.g. headers/foo)
     *
     * @return mixed|null
     */
    public function getDefaultOption($keyOrPath)
    {
        return $this->config->getPath("defaults/{$keyOrPath}");
    }

    public function createRequest($method, $url = null, array $headers = [], $body = null, array $options = [])
    {
        // Merge in default options
        if ($default = $this->config->get('defaults')) {
            $options = array_replace_recursive($default, $options);
        }

        // Use a clone of the /* Replaced /* Replaced /* Replaced client */ */ */'s event dispatcher
        $options['constructor_options'] = ['event_dispatcher' => clone $this->getEventDispatcher()];

        $request = $this->messageFactory->createRequest(
            $method,
            $url ? (string) $this->buildUrl($url) : (string) $this->getBaseUrl(),
            $headers,
            $body,
            $options
        );

        return $request;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function get($url = null, array $headers = [], $options = [])
    {
        return $this->send($this->createRequest('GET', $url, $headers, null, $options));
    }

    public function head($url = null, array $headers = [], array $options = [])
    {
        return $this->send($this->createRequest('HEAD', $url, $headers, null, $options));
    }

    public function delete($url = null, array $headers = [], array $options = [])
    {
        return $this->send($this->createRequest('DELETE', $url, $headers, null, $options));
    }

    public function put($url = null, array $headers = [], $body = null, array $options = [])
    {
        return $this->send($this->createRequest('PUT', $url, $headers, $body, $options));
    }

    public function patch($url = null, array $headers = [], $body = null, array $options = [])
    {
        return $this->send($this->createRequest('PATCH', $url, $headers, $body, $options));
    }

    public function post($url = null, array $headers = [], $body = null, array $options = [])
    {
        return $this->send($this->createRequest('POST', $url, $headers, $body, $options));
    }

    public function options($url = null, array $headers = [], array $options = [])
    {
        return $this->send($this->createRequest('OPTIONS', $url, $headers, null, $options));
    }

    public function send(RequestInterface $request)
    {
        $transaction = new Transaction($this, $request);
        if ($response = $this->adapter->send($transaction)) {
            return $response;
        }

        throw new \RuntimeException('No response was associated with the transaction');
    }

    public function sendAll($requests, array $options = [])
    {
        $requests = function() use ($requests, $options) {
            foreach ($requests as $request) {
                /** @var RequestInterface $request */
                if (isset($options['before'])) {
                    $request->getEventDispatcher()->addListener(RequestEvents::BEFORE_SEND, $options['before'], -255);
                }
                if (isset($options['complete'])) {
                    $request->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, $options['complete'], -255);
                }
                if (isset($options['error'])) {
                    $request->getEventDispatcher()->addListener(RequestEvents::ERROR, $options['error'], -255);
                }
                yield new Transaction($this, $request);
            }
        };

        $this->batchAdapter->batch($requests(), isset($options['parallel']) ? $options['parallel'] : 50);
    }

    /**
     * Get an array of default options to apply to the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'allow_redirects' => true,
            'exceptions'      => true,
            'verify'          => __DIR__ . '/Resources/cacert.pem'
        ];
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
            // Use absolute URLs as is
            return substr($url, 0, 4) === 'http'
                ? (string) $url
                : (string) Url::fromString($this->getBaseUrl())->combine($url);
        }

        list($url, $templateVars) = $url;
        if (substr($url, 0, 4) === 'http') {
            return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\uriTemplate($url, $templateVars);
        }

        return (string) Url::fromString($this->getBaseUrl())
            ->combine(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\uriTemplate($url, $templateVars));
    }

    /**
     * Get a default batch adapter to use based on the environment
     *
     * @return BatchAdapterInterface|null
     * @throws \RuntimeException
     */
    private function getDefaultBatchAdapter()
    {
        return extension_loaded('curl')
            ? new CurlAdapter($this->messageFactory)
            : new FakeBatchAdapter($this->adapter);
    }

    /**
     * Get a default adapter to use based on the environment
     *
     * @return AdapterInterface
     * @throws \RuntimeException
     */
    private function getDefaultAdapter()
    {
        if (extension_loaded('curl')) {
            if (!ini_get('allow_url_fopen')) {
                return $this->batchAdapter = $this->adapter = new CurlAdapter($this->messageFactory);
            } else {
                // Assume that the batch adapter will also be this CurlAdapter
                $this->batchAdapter = new CurlAdapter($this->messageFactory);
                return new StreamingProxyAdapter(
                    $this->batchAdapter,
                    new StreamAdapter($this->messageFactory)
                );
            }
        } elseif (ini_get('allow_url_fopen')) {
            return new StreamAdapter($this->messageFactory);
        } else {
            throw new \RuntimeException('The curl extension must be installed or you must set allow_url_fopen to true');
        }
    }
}
