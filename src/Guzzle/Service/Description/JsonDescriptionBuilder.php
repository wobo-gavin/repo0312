<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\JsonLoader;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\DescriptionBuilderException;

/**
 * Build service descriptions using a JSON document
 */
class JsonDescriptionBuilder implements DescriptionBuilderInterface
{
    /**
     * @var JsonLoader
     */
    protected $loader;

    /**
     * {@inheritdoc}
     */
    public function build($config, array $options = null)
    {
        if (!$this->loader) {
            $this->loader = new JsonLoader();
        }

        return ServiceDescription::factory($this->loader->parseJsonFile($config));
    }
}
