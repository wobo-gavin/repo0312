<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\XmlDescriptionBuilder;

abstract class AbstractCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getClient()
    {
        $service = XmlDescriptionBuilder::build(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'test_service.xml');
        $/* Replaced /* Replaced /* Replaced client */ */ */ =  new Client('http://www.google.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($service);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}