<?php

require_once 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' => 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//src',
    'Symfony\\Component\\Validator' => 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//vendor/symfony/validator',
    'Symfony\\Component\\EventDispatcher' => 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//vendor/symfony/event-dispatcher',
    'Doctrine' => 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//vendor/doctrine/common/lib',
    'Monolog' => 'phar:///* Replaced /* Replaced /* Replaced Guzzle */ */ *//vendor/monolog/monolog/src'
));
$classLoader->register();

__HALT_COMPILER();
