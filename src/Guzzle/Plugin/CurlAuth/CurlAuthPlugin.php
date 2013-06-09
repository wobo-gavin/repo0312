<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\CurlAuth;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Version;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds specified curl auth to all requests sent from a /* Replaced /* Replaced /* Replaced client */ */ */. Defaults to CURLAUTH_BASIC if none supplied.
 * @deprecated Use $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->setPath('request.options/auth', array('user', 'pass', 'Basic|Digest');
 */
class CurlAuthPlugin implements EventSubscriberInterface
{
    private $username;
    private $password;
    private $scheme;

    /**
     * @param string $username HTTP basic auth username
     * @param string $password Password
     * @param int    $scheme   Curl auth scheme
     */
    public function __construct($username, $password, $scheme=CURLAUTH_BASIC)
    {
        Version::warn(__CLASS__ . " is deprecated. Use \$/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->setPath('request.options/auth', array('user', 'pass', 'Basic|Digest');");
        $this->username = $username;
        $this->password = $password;
        $this->scheme = $scheme;
    }

    public static function getSubscribedEvents()
    {
        return array('/* Replaced /* Replaced /* Replaced client */ */ */.create_request' => array('onRequestCreate', 255));
    }

    /**
     * Add basic auth
     *
     * @param Event $event
     */
    public function onRequestCreate(Event $event)
    {
        $event['request']->setAuth($this->username, $this->password, $this->scheme);
    }
}
