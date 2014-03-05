<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\CookieJar\ArrayCookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\CookieJar\CookieJarInterface;

/**
 * Adds, extracts, and persists cookies between HTTP requests
 */
class Cookie implements SubscriberInterface
{
    /** @var CookieJarInterface Cookie cookieJar used to hold cookies */
    private $cookieJar;

    /**
     * @param CookieJarInterface $cookieJar Cookie jar used to hold cookies
     */
    public function __construct(CookieJarInterface $cookieJar = null)
    {
        $this->cookieJar = $cookieJar ?: new ArrayCookieJar();
    }

    public static function getSubscribedEvents()
    {
        return [
            'before'   => ['onRequestBeforeSend', 125],
            'complete' => ['onRequestSent', 125]
        ];
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

    public function onRequestBeforeSend(BeforeEvent $event)
    {
        $event->getRequest()->removeHeader('Cookie');

        // Find cookies that match this request
        if ($matching = $this->cookieJar->getMatchingCookies($event->getRequest())) {
            $cookies = [];
            foreach ($matching as $cookie) {
                $cookies[] = $cookie->getName()
                    . '=' . $this->getCookieValue($cookie->getValue());
            }
            $event->getRequest()->setHeader('Cookie', implode(';', $cookies));
        }
    }

    public function onRequestSent(CompleteEvent $event)
    {
        $this->cookieJar->addCookiesFromResponse(
            $event->getRequest(),
            $event->getResponse()
        );
    }

    private function getCookieValue($value)
    {
        // Quote the cookie value if it is not already quoted and it contains
        // problematic characters.
        if (substr($value, 0, 1) !== '"' &&
            substr($value, -1, 1) !== '"' &&
            strpbrk($value, ';,')
        ) {
            $value = '"' . $value . '"';
        }

        return $value;
    }
}
