<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\MessageFormatter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Plugin class that will add request and response logging to an HTTP request.
 *
 * The log plugin uses a message formatter that allows custom messages via template variable substitution.
 *
 * @see MessageLogger for a list of available log template variable substitutions
 */
class LogPlugin implements EventSubscriberInterface
{
    /**
     * @var LogAdapterInterface Adapter responsible for writing log data
     */
    private $logAdapter;

    /**
     * @var MessageFormatter Formatter used to format messages before logging
     */
    protected $formatter;

    /**
     * @var bool Whether or not to wire request and response bodies
     */
    protected $wireBodies;

    /**
     * Construct a new LogPlugin
     *
     * @param LogAdapterInterface     $logAdapter Adapter object used to log message
     * @param string|MessageFormatter $formatter  Formatter used to format log messages or the formatter template
     * @param bool                    $wireBodies Set to true to track request and response bodies using a temporary
     *                                            buffer if the bodies are not repeatable.
     */
    public function __construct(
        LogAdapterInterface $logAdapter,
        $formatter = null,
        $wireBodies = false
    ) {
        $this->logAdapter = $logAdapter;
        $this->formatter = $formatter instanceof MessageFormatter ? $formatter : new MessageFormatter($formatter);
        $this->wireBodies = $wireBodies;
    }

    /**
     * Get a log plugin that outputs full request, response, and curl error information to stderr
     *
     * @param bool $wireBodies Set to false to disable request/response body output when they use are not repeatable
     *
     * @return self
     */
    public static function getDebugPlugin($wireBodies = true)
    {
        return new self(new ClosureLogAdapter(function ($m) {
            fwrite(STDERR, $m . PHP_EOL);
        }), "# Request:\n{request}\n\n# Response:\n{response}\n\n# Errors: {curl_code} {curl_error}", $wireBodies);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'curl.callback.write' => array('onCurlWrite', 255),
            'curl.callback.read'  => array('onCurlRead', 255),
            'request.before_send' => array('onRequestBeforeSend', 255),
            'request.sent'        => array('onRequestSent', 255)
        );
    }

    /**
     * Event triggered when curl data is read from a request
     *
     * @param Event $event
     */
    public function onCurlRead(Event $event)
    {
        // Stream the request body to the log if the body is not repeatable
        if ($wire = $event['request']->getParams()->get('request_wire')) {
            $wire->write($event['read']);
        }
    }

    /**
     * Event triggered when curl data is written to a response
     *
     * @param Event $event
     */
    public function onCurlWrite(Event $event)
    {
        // Stream the response body to the log if the body is not repeatable
        if ($wire = $event['request']->getParams()->get('response_wire')) {
            $wire->write($event['write']);
        }
    }

    /**
     * Called before a request is sent
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        if ($this->wireBodies) {
            $request = $event['request'];
            // Ensure that curl IO events are emitted
            $request->getParams()->set('curl.emit_io', true);
            // We need to make special handling for content wiring and non-repeatable streams.
            if ($request instanceof EntityEnclosingRequestInterface && $request->getBody()
                && (!$request->getBody()->isSeekable() || !$request->getBody()->isReadable())
            ) {
                // The body of the request cannot be recalled so logging the body will require us to buffer it
                $request->getParams()->set('request_wire', EntityBody::factory());
            }
            if (!$request->isResponseBodyRepeatable()) {
                // The body of the response cannot be recalled so logging the body will require us to buffer it
                $request->getParams()->set('response_wire', EntityBody::factory());
            }
        }
    }

    /**
     * Triggers the actual log write when a request completes
     *
     * @param Event $event
     */
    public function onRequestSent(Event $event)
    {
        $request = $event['request'];
        $response = $event['response'];

        $handle = $request->getParams()->get('curl_handle');

        if ($wire = $request->getParams()->get('request_wire')) {
            $request = clone $request;
            $request->setBody($wire);
        }

        if ($wire = $request->getParams()->get('response_wire')) {
            $response = clone $response;
            $response->setBody($wire);
        }

        // Send the log message to the adapter, adding a category and host
        $priority = $response && !$response->isSuccessful() ? LOG_ERR : LOG_DEBUG;
        $message = $this->formatter->format($request, $response, $handle);
        $this->logAdapter->log($message, $priority, array(
            'request'  => $request,
            'response' => $response,
            'handle'   => $handle
        ));
    }
}
