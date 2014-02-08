<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\MessageIntegrity;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\EventSubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;

/**
 * Verifies the message integrity of a response after all of the data has been received
 */
class FullResponseIntegrityPlugin implements EventSubscriberInterface
{
    private $hash;
    private $header;
    private $sizeCutoff;

    public function __construct($header, HashInterface $hash, $sizeCutoff = null)
    {
        $this->header = $header;
        $this->hash = $hash;
        $this->sizeCutoff = $sizeCutoff;
    }

    public static function getSubscribedEvents()
    {
        return ['request.after_send' => ['onRequestAfterSend', -1]];
    }

    public function onRequestAfterSend(RequestAfterSendEvent $event)
    {
        if ($this->canValidate($event->getResponse())) {
            $response = $event->getResponse();
            $this->matchesHash(
                $event,
                (string) $response->getHeader($this->header),
                $response->getBody()
            );
        }
    }

    private function canValidate(ResponseInterface $response)
    {
        if (!($body = $response->getBody())) {
            return false;
        } elseif (!$response->hasHeader($this->header)) {
            return false;
        } elseif ($response->hasHeader('Transfer-Encoding')) {
            // Currently does not support un-gzipping or inflating responses
            return false;
        } elseif (!$body->isSeekable()) {
            return false;
        } elseif ($this->sizeCutoff !== null && $body->getSize() > $this->sizeCutoff) {
            return false;
        }

        return true;
    }

    private function matchesHash(RequestAfterSendEvent $event, $hash, StreamInterface $body)
    {
        $body->seek(0);
        while (!$body->eof()) {
            $this->hash->update($body->read(16384));
        }

        $result = base64_encode($this->hash->complete());
        if ($hash !== $result) {
            throw new MessageIntegrityException(
                sprintf(
                    '%s message integrity check failure. Expected "%s" but got "%s"',
                    $this->header, $hash, $result
                ),
                $event->getRequest(),
                $event->getResponse()
            );
        }
    }
}
