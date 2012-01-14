<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds HTTP basic auth to all requests sent from a /* Replaced /* Replaced /* Replaced client */ */ */
 */
class BasicAuthPlugin implements EventSubscriberInterface
{
    private $username;
    private $password;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array('/* Replaced /* Replaced /* Replaced client */ */ */.create_request' => 'onRequestCreate');
    }

    /**
     * Constructor
     *
     * @param string $username HTTP basic auth username
     * @param string $password Password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Add basic auth
     *
     * @param Event $event
     */
    public function onRequestCreate(Event $event)
    {
        $request = $event['request'];
        $request->setAuth($this->username, $this->password);
    }
}