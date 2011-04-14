<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 *
 * Autoloader stub for /* Replaced /* Replaced /* Replaced guzzle */ */ */.phar to autoload /* Replaced /* Replaced /* Replaced Guzzle */ */ */\* classes.
 *
 * Note: this autoloader does not load other PSR-0 libraries.  If you need to
 * autoload other libraries, we recommend the Symfony ClassLoader component.
 */

spl_autoload_register(function($class) {
    if (0 === strpos($class, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\')) {
        $path = 'phar://' . __FILE__ . DIRECTORY_SEPARATOR;
        if ('\\' != DIRECTORY_SEPARATOR) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        }
        $path .= $class . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});

__HALT_COMPILER();