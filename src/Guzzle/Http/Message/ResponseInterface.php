<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

interface ResponseInterface extends MessageInterface
{
    /**
     * Set the response status
     *
     * @param string $statusCode   Response status code to set (e.g., "200")
     * @param string $reasonPhrase Response reason phrase (e.g., "OK")
     *
     * @return self
     */
    public function setStatus($statusCode, $reasonPhrase = null);

    /**
     * Get the response status code (e.g. "200", "404", etc)
     *
     * @return string
     */
    public function getStatusCode();

    /**
     * Get the response reason phrase- a human readable version of the numeric
     * status code
     *
     * @return string
     */
    public function getReasonPhrase();

    /**
     * Get the effective URL that resulted in this response (e.g. the last redirect URL)
     *
     * @return string
     */
    public function getEffectiveUrl();

    /**
     * Set the effective URL that resulted in this response (e.g. the last redirect URL)
     *
     * @param string $url Effective URL
     *
     * @return self
     */
    public function setEffectiveUrl($url);

    /**
     * Parse the JSON response body and return an array
     *
     * @return array|string|int|bool|float
     * @throws \RuntimeException if the response body is not in JSON format
     */
    public function json();

    /**
     * Parse the XML response body and return a SimpleXMLElement
     *
     * @return \SimpleXMLElement
     * @throws \RuntimeException if the response body is not in XML format
     */
    public function xml();
}
