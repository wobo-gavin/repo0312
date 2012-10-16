<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

abstract class AbstractCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');

        return $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription(ServiceDescription::factory(__DIR__ . '/../../TestData/test_service.json'));
    }
}
