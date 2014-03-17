<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\MultiAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\FakeParallelAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\ParallelAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamingProxyAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionIterator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * HTTP /* Replaced /* Replaced /* Replaced client */ */ */
 */
class Client implements ClientInterface
{
    use HasEmitterTrait;

    const DEFAULT_CONCURRENCY = 25;

    /** @var MessageFactoryInterface Request factory used by the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $messageFactory;

    /** @var AdapterInterface */
    private $adapter;

    /** @var ParallelAdapterInterface */
    private $parallelAdapter;

    /** @var Url Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ */
    private $baseUrl;

    /** @var Collection Configuration data */
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
     *     - base_url: Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */ that is merged into relative URLs.
     *       Can be a string or an array that contains a URI template followed
     *       by an associative array of expansion variables to inject into the
     *       URI template.
     *     - adapter: Adapter used to transfer requests
     *     - parallel_adapter: Adapter used to transfer requests in parallel
     *     - message_factory: Factory used to create request and response object
     *     - defaults: Default request options to apply to each request
     */
    public function __construct(array $config = [])
    {
        $this->configureBaseUrl($config);
        $this->configureDefaults($config);
        $this->configureAdapter($config);
        $this->config = new Collection($config);
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

    public function __call($name, $arguments)
    {
        return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\deprecation_proxy(
            $this,
            $name,
            $arguments,
            ['getEventDispatcher' => 'getEmitter']
        );
    }

    public function getConfig($keyOrPath = null)
    {
        return $keyOrPath === null
            ? $this->config->toArray()
            : $this->config->getPath($keyOrPath);
    }

    public function setConfig($keyOrPath, $value)
    {
        // Ensure that "defaults" is always an array
        if ($keyOrPath == 'defaults' && !is_array($value)) {
            throw new \InvalidArgumentException('"defaults" must be an array');
        }

        $this->config->setPath($keyOrPath, $value);
    }

    public function createRequest($method, $url = null, array $options = [])
    {
        // Merge in default options
        $options = array_replace_recursive($this->config['defaults'], $options);

        // Use a clone of the /* Replaced /* Replaced /* Replaced client */ */ */'s emitter
        $options['config']['emitter'] = clone $this->getEmitter();

        $request = $this->messageFactory->createRequest(
            $method,
            $url ? (string) $this->buildUrl($url) : (string) $this->baseUrl,
            $options
        );

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
        $transaction = new Transaction($this, $request);
        try {
            if ($response = $this->adapter->send($transaction)) {
                return $response;
            }
            throw new \LogicException('No response was associated with the transaction');
        } catch (RequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            // Wrap exceptions in a RequestException to adhere to the interface
            throw new RequestException($e->getMessage(), $request, null, $e);
        }
    }

    public function sendAll($requests, array $options = [])
    {
        if (!($requests instanceof TransactionIterator)) {
            $requests = new TransactionIterator($requests, $this, $options);
        }

        $this->parallelAdapter->sendAll(
            $requests,
            isset($options['parallel'])
                ? $options['parallel']
                : self::DEFAULT_CONCURRENCY
        );
    }

    /**
     * Get an array of default options to apply to the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $verify =  __DIR__ . '/cacert.pem';

        // Use the bundled cacert if it is a regular file, or set to true if
        // using a phar file (because curL and the stream wrapper can't read
        // cacerts from the phar stream wrapper). Favor the ini setting over
        // the system's cacert.
        if (substr(__FILE__, 0, 7) == 'phar://') {
            $verify = ini_get('openssl.cafile') ?: true;
        }

        return [
            'allow_redirects' => true,
            'exceptions'      => true,
            'verify'          => $verify
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
            if (strpos($url, '://')) {
                return (string) $url;
            }
            return (string) $this->baseUrl->combine($url);
        } elseif (strpos($url[0], '://')) {
            return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\uri_template($url[0], $url[1]);
        }

        return (string) $this->baseUrl->combine(
            \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\uri_template($url[0], $url[1])
        );
    }

    /**
     * Get a default parallel adapter to use based on the environment
     *
     * @return ParallelAdapterInterface|null
     * @throws \RuntimeException
     */
    private function getDefaultParallelAdapter()
    {
        return extension_loaded('curl')
            ? new CurlAdapter($this->messageFactory)
            : new FakeParallelAdapter($this->adapter);
    }

    /**
     * Create a default adapter to use based on the environment
     * @throws \RuntimeException
     */
    private function getDefaultAdapter()
    {
        if (extension_loaded('curl')) {
            $this->parallelAdapter = new MultiAdapter($this->messageFactory);
            $this->adapter = function_exists('curl_reset')
                ? new CurlAdapter($this->messageFactory)
                : $this->parallelAdapter;
            if (ini_get('allow_url_fopen')) {
                $this->adapter = new StreamingProxyAdapter(
                    $this->adapter,
                    new StreamAdapter($this->messageFactory)
                );
            }
        } elseif (ini_get('allow_url_fopen')) {
            $this->adapter = new StreamAdapter($this->messageFactory);
        } else {
            throw new \RuntimeException('/* Replaced /* Replaced /* Replaced Guzzle */ */ */ require\'s cURL, the '
                . 'allow_url_fopen ini setting, or a custom HTTP adapter.');
        }
    }

    private function configureBaseUrl(&$config)
    {
        if (!isset($config['base_url'])) {
            $this->baseUrl = new Url('', '');
        } elseif (is_array($config['base_url'])) {
            $this->baseUrl = Url::fromString(
                \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\uri_template(
                    $config['base_url'][0],
                    $config['base_url'][1]
                )
            );
            $config['base_url'] = (string) $this->baseUrl;
        } else {
            $this->baseUrl = Url::fromString($config['base_url']);
        }
    }

    private function configureDefaults(&$config)
    {
        if (isset($config['defaults'])) {
            $config['defaults'] = array_replace(
                $this->getDefaultOptions(),
                $config['defaults']
            );
        } else {
            $config['defaults'] = $this->getDefaultOptions();
        }

        // Add the default user-agent header
        if (!isset($config['defaults']['headers'])) {
            $config['defaults']['headers'] = [
                'User-Agent' => static::getDefaultUserAgent()
            ];
        } elseif (!isset(array_change_key_case($config['defaults']['headers'])['user-agent'])) {
            // Add the User-Agent header if one was not already set
            $config['defaults']['headers']['User-Agent'] = static::getDefaultUserAgent();
        }
    }

    private function configureAdapter(&$config)
    {
        if (isset($config['message_factory'])) {
            $this->messageFactory = $config['message_factory'];
            unset($config['message_factory']);
        } else {
            $this->messageFactory = new MessageFactory();
        }
        if (isset($config['adapter'])) {
            $this->adapter = $config['adapter'];
            unset($config['adapter']);
        } else {
            $this->getDefaultAdapter();
        }
        // If no parallel adapter was explicitly provided and one was not
        // defaulted when creating the default adapter, then create one now.
        if (isset($config['parallel_adapter'])) {
            $this->parallelAdapter = $config['parallel_adapter'];
            unset($config['parallel_adapter']);
        } elseif (!$this->parallelAdapter) {
            $this->parallelAdapter = $this->getDefaultParallelAdapter();
        }
    }
}
