<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcher;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\HeaderInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Form\FormFileCollection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Form\MultipartBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\Url;

/**
 * HTTP request class to send requests
 */
class Request extends AbstractMessage implements RequestInterface
{
    use HasDispatcher;

    /** @var Url HTTP Url */
    private $url;

    /** @var string HTTP method (GET, PUT, POST, DELETE, HEAD, OPTIONS, TRACE) */
    private $method;

    /** @var Collection Transfer options */
    private $transferOptions;

    /** @var QueryString Form fields */
    private $formFields;

    /** @var FormFileCollection Form files */
    private $formFiles;

    /**
     * @param string           $method  HTTP method
     * @param string|Url       $url     HTTP URL to connect to. The URI scheme, host header, and URI are parsed from the
     *                                  full URL. If query string parameters are present they will be parsed as well.
     * @param array|Collection $headers HTTP headers
     * @param mixed            $body    Body to send with the request
     */
    public function __construct($method, $url, $headers = array(), $body = null)
    {
        parent::__construct();
        $this->method = strtoupper($method);
        $this->transferOptions = new Collection();
        $this->setUrl($url);

        if ($body) {
            $this->setBody($body);
        }

        if ($headers) {
            // Special handling for multi-value headers
            foreach ($headers as $key => $value) {
                // Deal with collisions with Host and Authorization
                if ($key == 'host' || $key == 'Host') {
                    $this->setHeader($key, $value);
                } elseif ($value instanceof HeaderInterface) {
                    $this->addHeader($key, $value);
                } else {
                    foreach ((array) $value as $v) {
                        $this->addHeader($key, $v);
                    }
                }
            }
        }
    }

    public function __clone()
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher = clone $this->eventDispatcher;
        }
        if ($this->formFields) {
            $this->formFields = clone $this->formFields;
        }
        if ($this->formFiles) {
            $this->formFiles = clone $this->formFiles;
        }
        $this->transferOptions = clone $this->transferOptions;
        $this->url = clone $this->url;
        $this->headers = clone $this->headers;
    }

    public function serialize()
    {
        return json_encode(array(
            'method'  => $this->method,
            'url'     => $this->getUrl(),
            'headers' => $this->headers->toArray(),
            'body'    => (string) $this->body
        ));
    }

    public function unserialize($serialize)
    {
        $data = json_decode($serialize, true);
        $this->__construct($data['method'], $data['url'], $data['headers'], $data['body']);
    }

    public function getStartLine()
    {
        return trim($this->method . ' ' . $this->getResource()) . ' '
            . strtoupper(str_replace('https', 'http', $this->url->getScheme()))
            . '/' . $this->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body, $contentType = null)
    {
        parent::setBody($body, $contentType);

        // Use chunked Transfer-Encoding if there is no content-length header
        if ($body !== null && !$this->hasHeader('Content-Length') && '1.1' == $this->getProtocolVersion()) {
            $this->setHeader('Transfer-Encoding', 'chunked');
        }

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setUrl($url)
    {
        $this->url = $url instanceof Url ? $url : Url::fromString($url);
        // Update the port and host header
        $this->setPort($this->url->getPort());

        return $this;
    }

    public function getUrl()
    {
        return (string) $this->url;
    }

    public function getQuery()
    {
        return $this->url->getQuery();
    }

    public function setMethod($method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getScheme()
    {
        return $this->url->getScheme();
    }

    public function setScheme($scheme)
    {
        $this->url->setScheme($scheme);

        return $this;
    }

    public function getHost()
    {
        return $this->url->getHost();
    }

    public function setHost($host)
    {
        $this->url->setHost($host);
        $this->setPort($this->url->getPort());

        return $this;
    }

    public function getPath()
    {
        return '/' . ltrim($this->url->getPath(), '/');
    }

    public function setPath($path)
    {
        $this->url->setPath($path);

        return $this;
    }

    public function getPort()
    {
        return $this->url->getPort();
    }

    public function setPort($port)
    {
        $this->url->setPort($port);

        // Include the port in the Host header if it is not the default port for the scheme of the URL
        $scheme = $this->url->getScheme();
        if (($scheme == 'http' && $port != 80) || ($scheme == 'https' && $port != 443)) {
            $this->setHeader('Host', $this->url->getHost() . ':' . $port);
        } else {
            $this->setHeader('Host', $this->url->getHost());
        }

        return $this;
    }

    public function getResource()
    {
        $resource = $this->getPath();
        if ($query = (string) $this->url->getQuery()) {
            $resource .= '?' . $query;
        }

        return $resource;
    }

    public function getFormFields()
    {
        if (!$this->formFields) {
            $this->formFields = new QueryString();
        }

        return $this->formFields;
    }

    public function getFormFiles()
    {
        if (!$this->formFiles) {
            $this->formFiles = new FormFileCollection();
        }

        return $this->formFiles;
    }

    public function prepare()
    {
        // Set the appropriate Content-Type for a request if one is not set and there are form fields
        if (!$this->body) {
            if ($this->formFiles && count($this->formFiles)) {
                $body = MultipartBody::fromRequest($this);
                $this->setHeader('Content-Type', 'multipart/form-data; boundary=' . $body->getBoundary());
                $this->setBody($body);
            } elseif ($this->formFields && count($this->getFormFields())) {
                if (!$this->hasHeader('Content-Type')) {
                    $this->setHeader('Content-Type', 'application/x-www-form-urlencoded');
                }
                $this->setBody((string) $this->formFields);
            }
        }

        // Always add the Expect 100-Continue header if the body cannot be rewound
        if ($this->body && !$this->body->isSeekable()) {
            $this->setHeader('Expect', '100-Continue');
        }

        // Never send a Transfer-Encoding: chunked and Content-Length header in the same request
        if ((string) $this->getHeader('Transfer-Encoding') == 'chunked') {
            $this->removeHeader('Content-Length');
        }

        return $this;
    }

    public function getTransferOptions()
    {
        return $this->transferOptions;
    }
}
