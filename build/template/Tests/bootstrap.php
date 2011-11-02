<?php

require_once $_SERVER['GUZZLE'] . '/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' => $_SERVER['GUZZLE'] . '/src',
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests' => $_SERVER['GUZZLE'] . '/tests'
));
$loader->register();

spl_autoload_register(function($class) {
    if (0 === strpos($class, '${service.namespace}\\')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)) . '.php';
        require_once __DIR__ . '/../' . $path;
        return true;
    }
});

// Register services with the /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase::setServiceBuilder(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory(array(
    'test.${service.short_name}' => array(
        'class' => '${service.namespace}\${service./* Replaced /* Replaced /* Replaced client */ */ */_class}'
    )
)));