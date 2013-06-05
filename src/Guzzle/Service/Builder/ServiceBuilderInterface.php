<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException;

/**
 * Service builder used to store and build /* Replaced /* Replaced /* Replaced client */ */ */s or arbitrary data. Client configuration data can be supplied to tell
 * the service builder how to create and cache {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface} objects. Arbitrary data can be
 * supplied and accessed from a service builder. Arbitrary data and other /* Replaced /* Replaced /* Replaced client */ */ */s can be referenced by name in /* Replaced /* Replaced /* Replaced client */ */ */
 * configuration arrays to make them input for building other /* Replaced /* Replaced /* Replaced client */ */ */s (e.g. "{key}").
 */
interface ServiceBuilderInterface
{
    /**
     * Get a ClientInterface object or arbitrary data from the service builder
     *
     * @param string     $name      Name of the registered service or data to retrieve
     * @param bool|array $throwAway Only pertains to retrieving /* Replaced /* Replaced /* Replaced client */ */ */ objects built using a configuration array.
     *                              Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */ for later retrieval from the ServiceBuilder.
     *                              If an array is specified, that data will overwrite the configured params of the
     *                              /* Replaced /* Replaced /* Replaced client */ */ */ if the /* Replaced /* Replaced /* Replaced client */ */ */ implements {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\FromConfigInterface} and will
     *                              not store the /* Replaced /* Replaced /* Replaced client */ */ */ for later retrieval.
     *
     * @return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface|mixed
     * @throws ServiceNotFoundException when a /* Replaced /* Replaced /* Replaced client */ */ */ or data cannot be found by the given name
     */
    public function get($name, $throwAway = false);

    /**
     * Register a service or arbitrary data by name with the service builder
     *
     * @param string $key     Name of the /* Replaced /* Replaced /* Replaced client */ */ */ or data to register
     * @param mixed  $service Client configuration array or arbitrary data to register. The /* Replaced /* Replaced /* Replaced client */ */ */ configuration array
     *                        must include a 'class' (string) and 'params' (array) key.
     *
     * @return ServiceBuilderInterface
     */
    public function set($key, $service);
}
