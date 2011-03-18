<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;

/**
 * Default service /* Replaced /* Replaced /* Replaced client */ */ */ builder
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class DefaultBuilder extends AbstractBuilder
{
    /**
     * @var string Name of the class created by the builder
     */
    protected $class;

    /**
     * Build the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Client
     * @throws ServiceException if the class of the /* Replaced /* Replaced /* Replaced client */ */ */ is not set
     * @throws ServiceException if the class set cannot be found
     */
    public function build()
    {
        // Validate the builder configuration settings
        $this->validate();

        $class = $this->getClass();
        
        if (!$class) {
            throw new ServiceException(
                'No class has been specified on the builder'
            );
        }

        if (!class_exists($class)) {
            throw new ServiceException('Class ' . $class . ' does not exist');
        }

        $serviceDescription = false;
        $key = '/* Replaced /* Replaced /* Replaced guzzle */ */ */_service_' . md5($this->getClass());
        if ($this->cache) {
            $serviceDescription = $this->cache->fetch($key);
            if ($serviceDescription) {
                if (!is_object($serviceDescription)) {
                    $serviceDescription = unserialize($serviceDescription);
                }
                $this->config->set('_service_from_cache', $key);
            }
        }

        if (!$serviceDescription) {
            $builder = new ConcreteDescriptionBuilder($class, $this->getConfig()->get('base_url'));
            $serviceDescription = $builder->build();
            // If the description was built and a cache is set, cache it
            if ($this->cache) {
                $this->cache->save($key, serialize($serviceDescription), $this->cacheTtl);
            }
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->createClient($class)
            ->setConfig($this->config)
            ->setCommandFactory(new ConcreteCommandFactory($serviceDescription))
            ->setService($serviceDescription);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * Create the /* Replaced /* Replaced /* Replaced client */ */ */ object
     *
     * @param string $class Class of the /* Replaced /* Replaced /* Replaced client */ */ */ to create
     *
     * @return Client
     */
    protected function createClient($class)
    {
        return new $class($this->config->get('base_url'));
    }

    /**
     * Get the configuration object associated with the default builder
     *
     * @return Collection
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the class name of the /* Replaced /* Replaced /* Replaced client */ */ */ that will be built by the builder
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the class name of the /* Replaced /* Replaced /* Replaced client */ */ */ that will be built by the builder
     *
     * @param string $class Name of the class
     *
     * @return DefaultBuilder
     */
    public function setClass($class)
    {
        $this->class = str_replace('.', '\\', ucwords($class));

        return $this;
    }
}