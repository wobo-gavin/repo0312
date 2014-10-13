<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJarInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;

/**
 * Adds, extracts, and persists cookies between HTTP requests
 */
class Cookie implements SubscriberInterface
{
    /** @var CookieJarInterface */
    private $cookieJar;

    /**
     * @param CookieJarInterface $cookieJar Cookie jar used to hold cookies
     */
    public function __construct(CookieJarInterface $cookieJar = null)
    {
        $this->cookieJar = $cookieJar ?: new CookieJar();
    }

    public function getEvents()
    {
        // Fire the cookie plugin complete event before redirecting
        return [
            'before'   => ['onBefore'],
            'complete' => ['onComplete', RequestEvents::REDIRECT_RESPONSE + 10]
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

    public function onBefore(BeforeEvent $event)
    {
        $this->cookieJar->addCookieHeader($event->getRequest());
    }

    public function onComplete(CompleteEvent $event)
    {
        $this->cookieJar->extractCookies(
            $event->getRequest(),
            $event->getResponse()
        );
    }
}
