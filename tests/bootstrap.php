<?php

error_reporting(E_ALL | E_STRICT);

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer.lock')) {
    die("Dependencies must be installed using composer:\n\ncomposer.phar install --install-suggests\n\n"
        . "See https://github.com/composer/composer/blob/master/README.md for help with installing composer\n");
}

require_once 'PHPUnit/TextUI/TestRunner.php';

// Include the composer autoloader
$loader = require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . '.composer' . DIRECTORY_SEPARATOR . 'autoload.php';
// Register the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ test namespace
$loader->add('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests', __DIR__);

// Add the services file to the default service builder
$servicesFile = __DIR__ . DIRECTORY_SEPARATOR . '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'services.xml';
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase::setServiceBuilder(/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory($servicesFile));