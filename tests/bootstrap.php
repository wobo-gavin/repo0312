<?php

error_reporting(E_ALL | E_STRICT);

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer-test.lock')) {
    die("Dependencies must be installed using composer:\n\nCOMPOSER=composer-test.json composer.phar install\n\n"
        . "See https://github.com/composer/composer/blob/master/README.md for help with installing composer\n");
}

require_once 'PHPUnit/TextUI/TestRunner.php';

// Inclue the phar files if testing against the phars
if (get_cfg_var('/* Replaced /* Replaced /* Replaced guzzle */ */ */_phar')) {
    require get_cfg_var('/* Replaced /* Replaced /* Replaced guzzle */ */ */_phar');
}

// Include the composer autoloader
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . '.composer' . DIRECTORY_SEPARATOR . 'autoload.php';

// Add the services file to the default service builder
$servicesFile = __DIR__ . DIRECTORY_SEPARATOR . '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'services.xml';
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase::setServiceBuilder(/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory($servicesFile));