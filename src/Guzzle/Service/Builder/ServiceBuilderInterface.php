<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\FromConfigInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException;

/**
 * Service builder to generate service builders and service /* Replaced /* Replaced /* Replaced client */ */ */s from configuration settings
 */
interface ServiceBuilderInterface
{
    /**
     * Get a service using a registered builder
     *
     * @param string     $name      Name of the registered /* Replaced /* Replaced /* Replaced client */ */ */ to retrieve
     * @param bool|array $throwAway Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */ for later retrieval from the ServiceBuilder.
     *                              If an array is specified, that data will overwrite the configured params
     *
     * @return FromConfigInterface
     * @throws ServiceNotFoundException when a /* Replaced /* Replaced /* Replaced client */ */ */ cannot be found by name
     */
    public function get($name, $throwAway = false);

    /**
     * Register a service by name with the service builder
     *
     * @param string $key     Name of the /* Replaced /* Replaced /* Replaced client */ */ */ to register
     * @param mixed  $service Service to register
     *
     * @return ServiceBuilderInterface
     */
    public function set($key, $service);
}
