<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;

/**
 * Factory used to create fopen streams using PHP's http and https stream wrappers
 */
class PhpStreamRequestFactory implements StreamRequestFactoryInterface
{
    /**
     * @var array Stream context
     */
    protected $context;

    /**
     * @var Url Stream URL
     */
    protected $url;

    /**
     * {@inheritdoc}
     */
    public function fromRequest(RequestInterface $request, array $contextOptions = null)
    {
        // Add default HTTP context options
        $this->createDefaultContext($request);

        // Use the request's URL
        $this->setUrl($request);

        // Add SSL options if needed
        if ($request->getScheme() == 'https') {
            $this->addSslOptions($request);
        }

        // Add the content for the request if needed
        if ($request instanceof EntityEnclosingRequestInterface) {
            $this->addBodyOptions($request);
        }

        // Merge in custom context options
        if ($contextOptions) {
            $this->mergeContextOptions($contextOptions);
        }

        // Create the file handle but silence errors
        return $this->createStream();
    }

    /**
     * Adds the default context options to the stream context options
     *
     * @param RequestInterface $request Request
     */
    protected function createDefaultContext(RequestInterface $request)
    {
        $this->context = array(
            'http' => array(
                'method'           => $request->getMethod(),
                'header'           => $request->getHeaderLines(),
                'protocol_version' => '1.0',
                'ignore_errors'    => '1'
            )
        );
    }

    /**
     * Set the URL to use with the factory
     *
     * @param RequestInterface $request Request that owns the URL
     */
    protected function setUrl(RequestInterface $request)
    {
        $this->url = $request->getUrl(true);

        // Check for basic Auth username
        if ($request->getUsername()) {
            $this->url->setUsername($request->getUsername());
        }

        // Check for basic Auth password
        if ($request->getPassword()) {
            $this->url->setPassword($request->getPassword());
        }
    }

    /**
     * Add SSL options to the stream context
     *
     * @param RequestInterface $request Request
     */
    protected function addSslOptions(RequestInterface $request)
    {
        if ($verify = $request->getCurlOptions()->get(CURLOPT_SSL_VERIFYPEER)) {
            $this->context['ssl']['verify_peer'] = true;
        }

        if ($cafile = $request->getCurlOptions()->get(CURLOPT_CAINFO)) {
            $this->context['ssl']['cafile'] = $cafile;
        }
    }

    /**
     * Add body (content) specific options to the context options
     *
     * @param EntityEnclosingRequestInterface $request
     */
    protected function addBodyOptions(EntityEnclosingRequestInterface $request)
    {
        if ($request->getPostFields()) {
            $this->context['http']['content'] = (string) $request->getPostFields();
        } elseif ($request->getBody()) {
            $this->context['http']['content'] = (string) $request->getBody();
        }
        if ($this->context['http']['content']) {
            $this->context['http']['header'][] = 'Content-Length: ' . strlen($this->context['http']['content']);
        }
    }

    /**
     * Merge custom context options into the default context options
     *
     * @param array $contextOptions Context options
     */
    protected function mergeContextOptions(array $contextOptions)
    {
        foreach ($contextOptions as $wrapper => $options) {
            if (!isset($this->context[$wrapper])) {
                $this->context[$wrapper] = array();
            }
            if (is_array($options)) {
                foreach ($options as $optionName => $optionValue) {
                    $this->context[$wrapper][$optionName] = $optionValue;
                }
            }
        }
    }

    /**
     * Create the stream for the request with the context options
     *
     * @return resource
     * @throws RuntimeException If an error occurs
     */
    protected function createStream()
    {
        $level = error_reporting(0);
        $fp = fopen((string) $this->url, 'r', false, stream_context_create($this->context));
        error_reporting($level);
        if (false === $fp) {
            // If the file could not be created, then grab the last error and throw an exception
            $error = error_get_last();
            throw new RuntimeException($error['message']);
        }

        return $fp;
    }
}
