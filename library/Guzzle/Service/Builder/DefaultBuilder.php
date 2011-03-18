<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * Default service /* Replaced /* Replaced /* Replaced client */ */ */ builder
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class DefaultBuilder
{
    /**
     * Validate and prepare configuration parameters for a /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param array $config Configuration values to apply.  A 'base_url' array
     *      key is required, specifying the base URL of the web service.
     * @param array $defaults (optional) Default parameters
     * @param array $required (optional) Required parameter names
     *
     * @return Collection
     * @throws InvalidArgumentException if a base_url is not specified or missing argument
     */
    public static function prepareConfig(array $config = null, $defaults = null, $required = null)
    {
        $collection = new Collection((array) $defaults);
        foreach ((array) $config as $key => $value) {
            $collection->set($key, $value);
        }
        foreach ((array) $required as $key) {
            if (!$collection->hasKey($key)) {
                throw new \InvalidArgumentException(
                    "Client config must contain a '{$key}' key"
                );
            }
        }

        // Make sure that the service has a base_url specified
        if (!$collection->get('base_url')) {
            throw new \InvalidArgumentException(
                'No base_url is set in the builder config'
            );
        }

        return $collection;
    }

    /**
     * Build the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param Client $/* Replaced /* Replaced /* Replaced client */ */ */ Client object to add a command factory and description
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the service configuration settings
     * @param int $cacheTtl (optional) How long to cache data
     *
     * @return Client
     * @throws ServiceException if the class of the /* Replaced /* Replaced /* Replaced client */ */ */ is not set
     * @throws ServiceException if the class set cannot be found
     */
    public static function build(Client $/* Replaced /* Replaced /* Replaced client */ */ */, CacheAdapterInterface $cache = null, $cacheTtl = 86400)
    {
        $class = get_class($/* Replaced /* Replaced /* Replaced client */ */ */);
        $serviceDescription = false;
        $key = '/* Replaced /* Replaced /* Replaced guzzle */ */ */_' . str_replace('\\', '_', strtolower($class));
        
        if ($cache) {
            $serviceDescription = $cache->fetch($key);
            if ($serviceDescription) {
                if (!is_object($serviceDescription)) {
                    $serviceDescription = unserialize($serviceDescription);
                }
                $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('_service_from_cache', $key);
            }
        }

        if (!$serviceDescription) {          
            $builder = new ConcreteDescriptionBuilder($class, $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->get('base_url'));
            $serviceDescription = $builder->build();
            // If the description was built and a cache is set, cache it
            if ($cache) {
                $cache->save($key, serialize($serviceDescription), $cacheTtl);
            }
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory(new ConcreteCommandFactory($serviceDescription))
               ->setService($serviceDescription);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}