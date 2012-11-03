<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookieJar\CookieJarInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds, extracts, and persists cookies between HTTP requests
 */
class CookiePlugin implements EventSubscriberInterface
{
    /**
     * @var CookieJarInterface Cookie cookieJar used to hold cookies
     */
    protected $cookieJar;

    /**
     * @param CookieJarInterface $cookieJar Cookie jar used to hold cookies
     */
    public function __construct(CookieJarInterface $cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => array('onRequestBeforeSend', 125),
            'request.sent'        => array('onRequestSent', 125)
        );
    }

    /**
     * Get the cookie cookieJar
     *
     * @return CookieJarInterface
     */
    public function getCookieJar()
    {
        return $this->cookieJar;
    }

    /**
     * Add cookies before a request is sent
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $request = $event['request'];
        if (!$request->getParams()->get('cookies.disable')) {
            $request->removeHeader('Cookie');
            // Find cookies that match this request
            foreach ($this->cookieJar->getMatchingCookies($request) as $cookie) {
                $request->addCookie($cookie->getName(), $cookie->getValue());
            }
        }
    }

    /**
     * Extract cookies from a sent request
     *
     * @param Event $event
     */
    public function onRequestSent(Event $event)
    {
        $this->cookieJar->addCookiesFromResponse($event['response']);
    }
}