<?php

namespace ${service.namespace};

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @author ${service.author} <${service.email}>
 */
class ${service./* Replaced /* Replaced /* Replaced client */ */ */_class} extends Client
{
    /**
     * Factory method to create a new ${service./* Replaced /* Replaced /* Replaced client */ */ */_class}
     *
     * @param array|Collection $config Configuration data. Array keys:
     *    base_url - Base URL of web service
     *
     * @return ${service./* Replaced /* Replaced /* Replaced client */ */ */_class}
     *
     * @TODO update factory method and docblock for parameters
     */
    public static function factory($config)
    {
        $default = array();
        $required = array('base_url');
        $config = Inspector::prepareConfig($config, $default, $required);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new self($config->get('base_url'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig($config);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}