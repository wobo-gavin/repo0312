<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Service builder plugin used to add plugins to all /* Replaced /* Replaced /* Replaced client */ */ */s created by a
 * {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder}
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class PluginCollectionPlugin implements EventSubscriberInterface
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
     * Adds plugins to /* Replaced /* Replaced /* Replaced client */ */ */s as they are created by the service builder
     *
     * @param Event $event Event emitted
     */
    public function onCreateClient(Event $event)
    {
        foreach ($this->plugins as $plugin) {
            $event['/* Replaced /* Replaced /* Replaced client */ */ */']->addSubscriber($plugin);
        }
    }
}
