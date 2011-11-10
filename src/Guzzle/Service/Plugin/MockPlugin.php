<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Subject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\AbstractSubject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Queues mock responses and delivers mock responses in a fifo order.
 *
 * Signals emitted:
 *
 *  event           context             description
 *  -----           -------             -----------
 *  mock.request    RequestInterface    Received a request
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MockPlugin extends AbstractSubject implements Observer, \Countable
{
    /**
     * @var array Array of mock responses
     */
    protected $queue = array();

    /**
     * @var bool Whether or not to remove the plugin when the queue is empty
     */
    protected $temporary = false;

    /**
     * Get a mock response from a file
     *
     * @param string $file File to retreive a mock response from
     *
     * @return Response
     * @throws InvalidArgumentException if the file is not found
     */
    public static function getMockFile($path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('Unable to open mock file: ' . $path);
        }

        $parts = explode("\n\n", file_get_contents($path), 2);
        // Convert \n to \r\n in headers
        $data = isset($parts[1])
            ? str_replace("\n", "\r\n", $parts[0]) . "\r\n\r\n" . $parts[1]
            : $parts[0];

        return Response::factory($data);
    }

    /**
     * Constructor
     *
     * @param bool $temporary (optional) Set to TRUE to remove the plugin when
     *      the queue is empty
     */
    public function __construct($temporary = false)
    {
        $this->temporary = $temporary;
    }

    /**
     * Returns the number of remaining mock responses
     *
     * @return int
     */
    public function count()
    {
        return count($this->queue);
    }

    /**
     * Add a response to the end of the queue
     *
     * @param string|Response $response Response object or path to response file
     *
     * @return MockPlugin
     * @throws InvalidArgumentException if a string or Response is not passed
     */
    public function addResponse($response)
    {
        if (!($response instanceof Response)) {
            if (!is_string($response)) {
                throw new \InvalidArgumentException('Invalid response');
            }
            $response = self::getMockFile($response);
        }

        $this->queue[] = $response;

        return $this;
    }

    /**
     * Clear the queue
     *
     * @return MockPlugin
     */
    public function clearQueue()
    {
        $this->queue = array();

        return $this;
    }

    /**
     * Returns an array of mock responses remaining in the queue
     *
     * @return array
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Check if this is a temporary plugin
     *
     * @return bool
     */
    public function isTemporary()
    {
        return $this->temporary;
    }

    /**
     * Get a response from the front of the list and add it to a request
     *
     * @param RequestInterface $request Request to mock
     *
     * @return MockPlugin
     */
    public function dequeue(RequestInterface $request)
    {
        $this->getEventManager()->notify('mock.request', $request);
        $request->setResponse(array_shift($this->queue), true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Subject $subject, $event, $context = null)
    {
        if ($event == 'request.create' && !empty($this->queue)) {
            $this->dequeue($context);
            // Detach the filter from the /* Replaced /* Replaced /* Replaced client */ */ */ so it's a one-time use
            if ($this->temporary && empty($this->queue)) {
                $subject->getEventManager()->detach($this);
            }
        }
    }
}