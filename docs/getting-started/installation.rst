============
Installation
============

Requirements
------------

#. PHP 5.3.3+ compiled with the cURL extension
#. A recent version of cURL 7.16.2+ compiled with OpenSSL and zlib

Installing /* Replaced /* Replaced /* Replaced Guzzle */ */ */
-----------------

Composer
~~~~~~~~

The recommended way to install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is with `Composer <http://getcomposer.org>`_. Composer is a dependency
management tool for PHP that allows you to declare the dependencies your project needs and installs them into your
project.

.. code-block:: bash

    # Install Composer
    curl -sS https://getcomposer.org/installer | php

    # Add /* Replaced /* Replaced /* Replaced Guzzle */ */ */ as a dependency
    php composer.phar require /* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */:~3.7

After installing, you need to require Composer's autoloader:

.. code-block:: php

    require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining
dependencies at `getcomposer.org <http://getcomposer.org>`_.

Using only specific parts of /* Replaced /* Replaced /* Replaced Guzzle */ */ */
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While you can always just rely on ``/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */``, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides several smaller parts of /* Replaced /* Replaced /* Replaced Guzzle */ */ */ as individual
packages available through Composer.

+-----------------------------------------------------------------------------------------------+------------------------------------------+
| Package name                                                                                  | Description                              |
+===============================================================================================+==========================================+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//common <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//common>`_                               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common``               |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//http <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//http>`_                                   | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http``                 |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//parser <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//parser>`_                               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser``               |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//batch <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//batch>`_                                 | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch``                |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//cache <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//cache>`_                                 | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache``                |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//inflection <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//inflection>`_                       | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection``           |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//iterator <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//iterator>`_                           | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Iterator``             |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//log <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//log>`_                                     | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log``                  |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin>`_                               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin`` (all plugins) |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-async <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-async>`_                   | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Async``         |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-backoff <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-backoff>`_               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\BackoffPlugin`` |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-cache <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-cache>`_                   | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache``         |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-cookie <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-cookie>`_                 | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie``        |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-error-response <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-error-response>`_ | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse`` |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-history <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-history>`_               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History``       |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-log <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-log>`_                       | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log``           |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-md5 <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-md5>`_                       | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5``           |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-mock <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-mock>`_                     | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock``          |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-oauth <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//plugin-oauth>`_                   | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Oauth``         |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//service <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//service>`_                             | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service``              |
+-----------------------------------------------------------------------------------------------+------------------------------------------+
| `/* Replaced /* Replaced /* Replaced guzzle */ */ *//stream <https://packagist.org/packages//* Replaced /* Replaced /* Replaced guzzle */ */ *//stream>`_                               | Provides ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream``               |
+-----------------------------------------------------------------------------------------------+------------------------------------------+

Bleeding edge
^^^^^^^^^^^^^

During your development, you can keep up with the latest changes on the master branch by setting the version
requirement for /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to ``dev-master``.

.. code-block:: js

   {
      "require": {
         "/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */": "dev-master"
      }
   }

PEAR
~~~~

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ can be installed through PEAR:

.. code-block:: bash

    pear channel-discover /* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/pear
    pear install /* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */

You can install a specific version of /* Replaced /* Replaced /* Replaced Guzzle */ */ */ by providing a version number suffix:

.. code-block:: bash

    pear install /* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-3.7.0

Contributing to /* Replaced /* Replaced /* Replaced Guzzle */ */ */
----------------------

In order to contribute, you'll need to checkout the source from GitHub and install /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s dependencies using
Composer:

.. code-block:: bash

    git clone https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.git
    cd /* Replaced /* Replaced /* Replaced guzzle */ */ */ && curl -s http://getcomposer.org/installer | php && ./composer.phar install --dev

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is unit tested with PHPUnit. You will need to create your own phpunit.xml file in order to run the unit tests
(or just copy phpunit.xml.dist to phpunit.xml). Run the tests using the vendored PHPUnit binary:

.. code-block:: bash

    vendor/bin/phpunit

You'll need to install node.js v0.5.0 or newer in order to test the cURL implementation.

Framework integrations
----------------------

Using /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with Symfony
~~~~~~~~~~~~~~~~~~~~~~~~~

Bundles are available on GitHub:

- `Ddeboer/* Replaced /* Replaced /* Replaced Guzzle */ */ */Bundle <https://github.com/ddeboer//* Replaced /* Replaced /* Replaced Guzzle */ */ */Bundle>`_ for /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 2
- `Misd/* Replaced /* Replaced /* Replaced Guzzle */ */ */Bundle <https://github.com/misd-service-development//* Replaced /* Replaced /* Replaced guzzle */ */ */-bundle>`_ for /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3

Using /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with Silex
~~~~~~~~~~~~~~~~~~~~~~~

A `/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Silex service provider <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-silex-extension>`_ is available on GitHub.
