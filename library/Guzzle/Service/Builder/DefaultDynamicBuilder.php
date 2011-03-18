<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DynamicCommandFactory;

/**
 * Default service /* Replaced /* Replaced /* Replaced client */ */ */ builder for dynamic services based on a service
 * document
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class DefaultDynamicBuilder extends AbstractBuilder
{
    /**
     * @var CommandFactory Factory to build commands based on a description
     */
    protected $commandFactory;

    /**
     * @var ServiceDescription Service document describing the service
     */
    protected $service;

    /**
     * @var string Class name
     */
    protected $class;

    /**
     * Construct the DynamicClient builder using an XML document
     *
     * @param string $filename Full path to the service description document
     * @param array $config (optional) Configuration values to apply
     */
    public function __construct($filename, array $config = null)
    {
        $builder = new XmlDescriptionBuilder($filename);
        $this->service = $builder->build();
        $/* Replaced /* Replaced /* Replaced client */ */ */Args = $this->service->getClientArgs();
        $this->class = $/* Replaced /* Replaced /* Replaced client */ */ */Args['_/* Replaced /* Replaced /* Replaced client */ */ */_class']['value'];
        $this->commandFactory = new DynamicCommandFactory($this->service);
        $this->name = $this->service->getName() . ' Builder';
        $this->config = $config ?: array();
    }

    /**
     * Build the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return DynamicClient
     */
    public function build()
    {
        $class = $this->getClass();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new $class($this->service->getBaseUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig($this->config)
               ->setService($this->service)
               ->setCommandFactory($this->commandFactory);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}