<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpErrorPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Post\PostBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Post\PostFile;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\RedirectPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\Url;

/**
 * Default HTTP request factory used to create {@see Request} and {@see Response} objects
 */
class MessageFactory implements MessageFactoryInterface
{
    /** @var MessageFactory Singleton instance of the default request factory */
    private static $instance;

    /** @var HttpErrorPlugin */
    private $errorPlugin;

    /** @var RedirectPlugin */
    private $redirectPlugin;

    /**
     * Get a cached instance of the default request factory
     *
     * @return MessageFactory
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __construct()
    {
        $this->errorPlugin = new HttpErrorPlugin();
        $this->redirectPlugin = new RedirectPlugin();
    }

    public function createResponse()
    {
        return new Response();
    }

    public function createRequest(
        $method,
        $url,
        array $headers = [],
        $body = null,
        array $options = array()
    ) {
        $request = new Request($method, $url, $headers);

        if (isset($options['dispatcher'])) {
            $request->setEventDispatcher($options['dispatcher']);
            unset($options['dispatcher']);
        }

        if ($body) {
            if (is_array($body)) {
                $this->addPostData($request, $body);
            } else {
                $request->setBody($body, (string) $request->getHeader('Content-Type'));
            }
        }

        if ($options) {
            $this->applyOptions($request, $options);
        }

        return $request;
    }

    /**
     * Create a request using an array returned from a MessageParserInterface
     *
     * @param array $parsed Parsed request data
     *
     * @return RequestInterface
     */
    public function fromParsedRequest(array $parsed)
    {
        $request = $this->createRequest(
            $parsed['method'],
            Url::buildUrl($parsed['request_url']),
            $parsed['headers'],
            $parsed['body']
        )->setProtocolVersion($parsed['version']);

        // "Expect: 100-Continue" header is added when using a raw request body for PUT or POST requests.
        // This factory method should accurately reflect the message, so here we are removing the Expect
        // header if one was not supplied in the message.
        if (!isset($parsed['headers']['Expect']) && !isset($parsed['headers']['expect'])) {
            $request->removeHeader('Expect');
        }

        return $request;
    }

    /**
     * Apply POST fields and files to a request to attempt to give an accurate representation
     *
     * @param RequestInterface $request Request to update
     * @param array            $body    Body to apply
     */
    protected function addPostData(RequestInterface $request, array $body)
    {
        $post = new PostBody();
        foreach ($body as $key => $value) {
            if (is_string($value) || is_array($value)) {
                $post->setField($key, $value);
            } else {
                $post->addFile(PostFile::create($key, $value));
            }
        }

        $request->setBody($post);
        $post->applyRequestHeaders($request);
    }

    protected function applyOptions(RequestInterface $request, array $options = array())
    {
        static $methods;
        if (!$methods) {
            $methods = array_flip(get_class_methods(__CLASS__));
        }

        // Iterate over each key value pair and attempt to apply a config using function visitors
        foreach ($options as $key => $value) {
            $method = "visit_{$key}";
            if (isset($methods[$method])) {
                $this->{$method}($request, $value);
            } else {
                // Set on the transfer options by default if no visitor is found
                $request->getTransferOptions()[$key] = $value;
            }
        }
    }

    private function visit_allow_redirects(RequestInterface $request, $value)
    {
        if ($value !== false) {
            if ($value == 'strict') {
                $request->getTransferOptions()[RedirectPlugin::STRICT_REDIRECTS] = true;
            }
            $request->getEventDispatcher()->addSubscriber($this->redirectPlugin);
        }
    }

    private function visit_exceptions(RequestInterface $request, $value)
    {
        if ($value === true) {
            $request->getEventDispatcher()->addSubscriber($this->errorPlugin);
        }
    }

    private function visit_auth(RequestInterface $request, $value)
    {
        if (!is_array($value) || count($value) < 2) {
            throw new \InvalidArgumentException(
                'auth value must be an array that contains a username, password, and optional authentication scheme'
            );
        }

        if (!isset($auth[2]) || strtolower($auth[2]) == 'basic') {
            // We can easily handle simple basic Auth in the factory
            $request->setHeader('Authorization', 'Basic ' . base64_encode("$value[0]:$value[1]"));
        } else {
            // Rely on an adapter to implement the authorization protocol (e.g. cURL)
            $request->getTransferOptions()['auth'] = $value;
        }
    }

    private function visit_query(RequestInterface $request, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('query value must be an array');
        }

        // Do not overwrite existing query string variables
        $query = $request->getQuery();
        foreach ($value as $k => $v) {
            if (!isset($query[$k])) {
                $query[$k] = $v;
            }
        }
    }

    private function visit_headers(RequestInterface $request, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('header value must be an array');
        }

        // Do not overwrite existing headers
        foreach ($value as $k => $v) {
            if (!$request->hasHeader($k)) {
                $request->setHeader($k, $v);
            }
        }
    }

    private function visit_cookies(RequestInterface $request, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('cookies value must be an array');
        }

        foreach ($value as $name => $v) {
            $request->addHeader('Cookie')->add("{$name}={$v}");
        }
    }

    private function visit_events(RequestInterface $request, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('events value must be an array');
        }

        foreach ($value as $name => $method) {
            if (is_array($method)) {
                $request->getEventDispatcher()->addListener($name, $method[0], $method[1]);
            } else {
                $request->getEventDispatcher()->addListener($name, $method);
            }
        }
    }

    private function visit_plugins(RequestInterface $request, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('plugins value must be an array');
        }

        foreach ($value as $plugin) {
            $request->addSubscriber($plugin);
        }
    }
}
