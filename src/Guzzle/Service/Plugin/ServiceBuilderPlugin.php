<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Service builder plugin to add plugins to all service /* Replaced /* Replaced /* Replaced client */ */ */s
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class ServiceBuilderPlugin implements EventSubscriberInterface
{
    /**
     * @var $plugins array plugins to add
     */
    private $plugins = array();

    /**
     * @param array $plugins plugins to add
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */' => 'onCreateClient'
        );
    }

    /**
     * Add plugins when /* Replaced /* Replaced /* Replaced client */ */ */ whould create
     *
     * @param Event $event
     */
    public function onCreateClient(Event $event)
    {
        foreach ($this->plugins as $plugin) {
            $event['/* Replaced /* Replaced /* Replaced client */ */ */']->addSubscriber($plugin);
        }
    }
}
