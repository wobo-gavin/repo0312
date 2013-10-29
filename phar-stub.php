<?php

Phar::mapPhar('/* Replaced /* Replaced /* Replaced guzzle */ */ */.phar');

require_once 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/src',
    'Symfony\\Component\\EventDispatcher' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/symfony/event-dispatcher'
));
$classLoader->register();

__HALT_COMPILER();
