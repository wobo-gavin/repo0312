<?php

error_reporting(E_ALL | E_STRICT);

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

require_once 'PHPUnit/TextUI/TestRunner.php';

// Include the phar files if testing against the phars
if (get_cfg_var('/* Replaced /* Replaced /* Replaced guzzle */ */ */_phar')) {
    require get_cfg_var('/* Replaced /* Replaced /* Replaced guzzle */ */ */_phar');
}

// Include the composer autoloader
$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

// Add the services file to the default service builder
$servicesFile = __DIR__ . '//* Replaced /* Replaced /* Replaced Guzzle */ */ *//Tests/TestData/services/services.json';
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase::setServiceBuilder(/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory($servicesFile));
