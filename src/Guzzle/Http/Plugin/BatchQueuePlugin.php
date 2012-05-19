<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Queues requests and sends them in parallel when a flush event is recievied.
 * You can call the flush() method on the plugin or emit a 'flush' event from
 * the /* Replaced /* Replaced /* Replaced client */ */ */ on which the plugin is attached.
 *
 * This plugin probably will not work well with plugins that implicitly
 * send requests (ExponentialBackoffPlugin, CachePlugin) or CommandSets.
 */
class BatchQueuePlugin implements EventSubscriberInterface, \Countable
{
    private $autoFlushCount;
    private $queue = array();

    /**
     * @param int $autoFlushCount Set to >0 to automatically flush
     *     the queue when the number of requests is > $autoFlushCount
     */
    public function __construct($autoFlushCount = 0)
    {
        $this->autoFlushCount = $autoFlushCount;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            '/* Replaced /* Replaced /* Replaced client */ */ */.create_request' => array('onRequestCreate', -255),
            'request.before_send'   => array('onRequestBeforeSend', 255),
            'flush'                 => array('flush', -255)
        );
    }

    /**
     * Get a count of the requests in queue
     *
     * @return int
     */
    public function count()
    {
        return count($this->queue);
    }

    /**
     * Remove a request from the queue
     *
     * @param RequestInterface $request Request to remove
     *
     * @return BatchQueuePlugin
     */
    public function removeRequest(RequestInterface $request)
    {
        $this->queue = array_filter($this->queue, function($r) use ($request) {
            return $r !== $request;
        });

        return $this;
    }

    /**
     * Add request to the queue
     *
     * @param Event $event
     */
    public function onRequestCreate(Event $event)
    {
        $this->addRequest($event['request']);
    }

    /**
     * Add request to the queue
     *
     * @param RequestInterface $request Request to add
     *
     * @return BatchQueuePlugin
     */
    public function addRequest(RequestInterface $request)
    {
        $this->queue[] = $request;
        if ($this->autoFlushCount && count($this->queue) >= $this->autoFlushCount) {
            $this->flush();
        }

        return $this;
    }

    /**
     * Ensures that queued requests that get sent outside of the context
     * of the batch plugin get removed from the queue
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $this->removeRequest($event['request']);
    }

    /**
     * Flush the queue
     *
     * @param Event $event
     */
    public function flush()
    {
        $multis = array();
        // Prepare each request for their respective curl multi objects
        while ($request = array_shift($this->queue)) {
            $multi = $request->getClient()->getCurlMulti();
            $multi->add($request);
            if (!in_array($multi, $multis)) {
                $multis[] = $multi;
            }
        }
        foreach ($multis as $multi) {
            $multi->send();
        }
    }
}
