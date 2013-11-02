<?php

error_reporting(E_ALL | E_STRICT);

require_once 'PHPUnit/TextUI/TestRunner.php';
require dirname(__DIR__) . '/vendor/autoload.php';

// Add the services file to the default service builder
$servicesFile = __DIR__ . '//* Replaced /* Replaced /* Replaced Guzzle */ */ *//Tests/TestData/services/services.json';
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase::setServiceBuilder(/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory($servicesFile));
