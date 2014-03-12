<?php

Phar::mapPhar('/* Replaced /* Replaced /* Replaced guzzle */ */ */.phar');

require_once 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/src',
    'Symfony\\Component\\EventDispatcher' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/symfony/event-dispatcher',
    'Doctrine' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/doctrine/common/lib',
    'Monolog' => 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/vendor/monolog/monolog/src'
));
$classLoader->register();

// Copy the cacert.pem file from the phar if it is not in the temp folder.
$from = 'phar:///* Replaced /* Replaced /* Replaced guzzle */ */ */.phar/src//* Replaced /* Replaced /* Replaced Guzzle */ */ *//Http/Resources/cacert.pem';
$certFile = sys_get_temp_dir() . '//* Replaced /* Replaced /* Replaced guzzle */ */ */-cacert.pem';

if (!copy($from, $certFile)) {
    throw new RuntimeException("Could not copy {$from} to {$certFile}: "
        . var_export(error_get_last(), true));
}

__HALT_COMPILER();
