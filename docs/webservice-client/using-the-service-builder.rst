=======================
Using a service builder
=======================

The best way to instantiate /* Replaced /* Replaced /* Replaced Guzzle */ */ */ web service /* Replaced /* Replaced /* Replaced client */ */ */s is to let /* Replaced /* Replaced /* Replaced Guzzle */ */ */ handle building the /* Replaced /* Replaced /* Replaced client */ */ */s for you using a
ServiceBuilder. A ServiceBuilder is responsible for creating concrete /* Replaced /* Replaced /* Replaced client */ */ */ objects based on configuration settings
and helps to manage credentials for different environments.

You don't have to use a service builder, but they help to decouple your application from concrete classes and help to
share configuration data across multiple /* Replaced /* Replaced /* Replaced client */ */ */s. Consider the following example. Here we are creating two /* Replaced /* Replaced /* Replaced client */ */ */s that
require the same API public key and secret key. The /* Replaced /* Replaced /* Replaced client */ */ */s are created using their ``factory()`` methods.

.. code-block:: php

    use MyService\FooClient;
    use MyService\BarClient;

    $foo = FooClient::factory(array(
        'key'    => 'abc',
        'secret' => '123',
        'custom' => 'and above all'
    ));

    $bar = BarClient::factory(array(
        'key'    => 'abc',
        'secret' => '123',
        'custom' => 'listen to me'
    ));

The redundant specification of the API keys can be removed using a service builder.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;

    $builder = ServiceBuilder::factory(array(
        'services' => array(
            'abstract_/* Replaced /* Replaced /* Replaced client */ */ */' => array(
                'params' => array(
                    'key'    => 'abc',
                    'secret' => '123'
                )
            ),
            'foo' => array(
                'extends' => 'abstract_/* Replaced /* Replaced /* Replaced client */ */ */',
                'class'   => 'MyService\FooClient',
                'params'  => array(
                    'custom' => 'and above all'
                )
            ),
            'bar' => array(
                'extends' => 'abstract_/* Replaced /* Replaced /* Replaced client */ */ */',
                'class'   => 'MyService\FooClient',
                'params'  => array(
                    'custom' => 'listen to me'
                )
            )
        )
    ));

    $foo = $builder->get('foo');
    $bar = $builder->get('bar');

You can make managing your API keys even easier by saving the service builder configuration in a JSON format in a
.json file.

Creating a service builder
--------------------------

A ServiceBuilder can source information from an array, an PHP include file that returns an array, or a JSON file.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;

    // Source service definitions from a JSON file
    $builder = ServiceBuilder::factory('services.json');

Sourcing data from an array
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Data can be source from a PHP array. The array must contain an associative ``services`` array that maps the name of a
/* Replaced /* Replaced /* Replaced client */ */ */ to the configuration information used by the service builder to create the /* Replaced /* Replaced /* Replaced client */ */ */. Clients are given names
which are used to identify how a /* Replaced /* Replaced /* Replaced client */ */ */ is retrieved from a service builder. This can be useful for using multiple
accounts for the same service or creating development /* Replaced /* Replaced /* Replaced client */ */ */s vs. production /* Replaced /* Replaced /* Replaced client */ */ */s.

.. code-block:: php

    $services = array(
        'includes' => array(
            '/path/to/other/services.json',
            '/path/to/other/php_services.php'
        ),
        'services' => array(
            'abstract.foo' => array(
                'params' => array(
                    'username' => 'foo',
                    'password' => 'bar'
                )
            ),
            'bar' => array(
                'extends' => 'abstract.foo',
                'class'   => 'MyClientClass',
                'params'  => array(
                    'other' => 'abc'
                )
            )
        )
    );

A service builder configuration array contains two top-level array keys:

+------------+---------------------------------------------------------------------------------------------------------+
| Key        | Description                                                                                             |
+============+=========================================================================================================+
| includes   | Array of paths to JSON or PHP include files to include in the configuration.                            |
+------------+---------------------------------------------------------------------------------------------------------+
| services   | Associative array of defined services that can be created by the service builder. Each service can      |
|            | contain the following keys:                                                                             |
|            |                                                                                                         |
|            | +------------+----------------------------------------------------------------------------------------+ |
|            | | Key        | Description                                                                            | |
|            | +============+========================================================================================+ |
|            | | class      | The concrete class to instantiate that implements the                                  | |
|            | |            | ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\FromConfigInterface``.                                                 | |
|            | +------------+----------------------------------------------------------------------------------------+ |
|            | | extends    | The name of a previously defined service to extend from                                | |
|            | +------------+----------------------------------------------------------------------------------------+ |
|            | | params     | Associative array of parameters to pass to the factory method of the service it is     | |
|            | |            | instantiated                                                                           | |
|            | +------------+----------------------------------------------------------------------------------------+ |
|            | | alias      | An alias that can be used in addition to the array key for retrieving a /* Replaced /* Replaced /* Replaced client */ */ */ from    | |
|            | |            | the service builder.                                                                   | |
|            | +------------+----------------------------------------------------------------------------------------+ |
+------------+---------------------------------------------------------------------------------------------------------+

The first /* Replaced /* Replaced /* Replaced client */ */ */ defined, ``abstract.foo``, is used as a placeholder of shared configuration values. Any service
extending abstract.foo will inherit its params. As an example, this can be useful when /* Replaced /* Replaced /* Replaced client */ */ */s share the same username
and password.

The next /* Replaced /* Replaced /* Replaced client */ */ */, ``bar``, extends from ``abstract.foo`` using the ``extends`` attribute referencing the /* Replaced /* Replaced /* Replaced client */ */ */ from
which to extend. Additional parameters can be merged into the original service definition when extending a parent
service.

.. important::

    Each /* Replaced /* Replaced /* Replaced client */ */ */ that you intend to instantiate must specify a ``class`` attribute that references the full class name
    of the /* Replaced /* Replaced /* Replaced client */ */ */ being created. The class referenced in the ``class`` parameter must implement a static ``factory()``
    method that accepts an array or ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection`` object and returns an instantiated object.

Sourcing from a PHP include
~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can create service builder configurations using a PHP include file. This can be useful if you wish to take
advantage of an opcode cache like APC to speed up the process of loading and processing the configuration. The PHP
include file is the same format as an array, but you simply create a PHP script that returns an array and save the
file with the .php file extension.

.. code-block:: php

    <?php return array('services' => '...');
    // Saved as config.php

This configuration file can then be used with a service builder.

.. code-block:: php

    $builder = ServiceBuilder::factory('/path/to/config.php');

Sourcing from a JSON document
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can use JSON documents to serialize your service descriptions. The JSON format uses the exact same structure as
the PHP array syntax, but it's just serialized using JSON.

.. code-block:: javascript

    {
        "includes": ["/path/to/other/services.json", "/path/to/other/php_services.php"],
        "services": {
            "abstract.foo": {
                "params": {
                    "username": "foo",
                    "password": "bar"
                }
            },
            "bar": {
                "extends": "abstract.foo",
                "class": "MyClientClass",
                "params": {
                    "other": "abc"
                }
            }
        }
    }

Referencing other /* Replaced /* Replaced /* Replaced client */ */ */s in parameters
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If one of your /* Replaced /* Replaced /* Replaced client */ */ */s depends on another /* Replaced /* Replaced /* Replaced client */ */ */ as one of its parameters, you can reference that /* Replaced /* Replaced /* Replaced client */ */ */ by name by
enclosing the /* Replaced /* Replaced /* Replaced client */ */ */'s reference key in ``{}``.

.. code-block:: javascript

    {
        "services": {
            "token": {
                "class": "My\Token\TokenFactory",
                "params": {
                    "access_key": "xyz"
                }
            },
            "/* Replaced /* Replaced /* Replaced client */ */ */": {
                "class": "My\Client",
                "params": {
                    "token_/* Replaced /* Replaced /* Replaced client */ */ */": "{token}",
                    "version": "1.0"
                }
            }
        }
    }

When ``/* Replaced /* Replaced /* Replaced client */ */ */`` is constructed by the service builder, the service builder will first create the ``token`` service
and then inject the token service into ``/* Replaced /* Replaced /* Replaced client */ */ */``'s factory method in the ``token_/* Replaced /* Replaced /* Replaced client */ */ */`` parameter.

Retrieving /* Replaced /* Replaced /* Replaced client */ */ */s from a service builder
-----------------------------------------

Clients are referenced using a customizable name you provide in your service definition. The ServiceBuilder is a sort
of multiton object-- it will only instantiate a /* Replaced /* Replaced /* Replaced client */ */ */ once and return that /* Replaced /* Replaced /* Replaced client */ */ */ for subsequent retrievals. Clients
are retrieved by name (the array key used in the configuration) or by the ``alias`` setting of a service.

Here's an example of retrieving a /* Replaced /* Replaced /* Replaced client */ */ */ from your ServiceBuilder:

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('foo');

    // You can also use the ServiceBuilder object as an array
    $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder['foo'];

Creating throwaway /* Replaced /* Replaced /* Replaced client */ */ */s
~~~~~~~~~~~~~~~~~~~~~~~~~~

You can get a "throwaway" /* Replaced /* Replaced /* Replaced client */ */ */ (a /* Replaced /* Replaced /* Replaced client */ */ */ that is not persisted by the ServiceBuilder) by passing ``true`` in the
second argument of ``ServiceBuilder::get()``. This allows you to create a /* Replaced /* Replaced /* Replaced client */ */ */ that will not be returned by other
parts of your code that use the service builder. Instead of passing ``true``, you can pass an array of configuration
settings that will override the configuration settings specified in the service builder.

.. code-block:: php

    // Get a throwaway /* Replaced /* Replaced /* Replaced client */ */ */ and overwrite the "custom" setting of the /* Replaced /* Replaced /* Replaced client */ */ */
    $foo = $builder->get('foo', array(
        'custom' => 'in this world there are rules'
    ));

Getting raw configuration settings
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can get the raw configuration settings provided to the service builder for a specific service using the
``getData($name)`` method of a service builder. This method will null if the service was not found in the service
builder or an array of configuration settings if the service was found.

.. code-block:: php

    $data = $builder->getData('foo');
    echo $data['key'] . "\n";
    echo $data['secret'] . "\n";
    echo $data['custom'] . "\n";

Adding a plugin to all /* Replaced /* Replaced /* Replaced client */ */ */s
------------------------------

You can add a plugin to all /* Replaced /* Replaced /* Replaced client */ */ */s created by a service builder using the ``addGlobalPlugin($plugin)`` method of a
service builder and passing a ``Symfony\Component\EventDispatcher\EventSubscriberInterface`` object. The service builder
will then attach each global plugin to every /* Replaced /* Replaced /* Replaced client */ */ */ as it is created. This allows you to, for example, add a LogPlugin
to every request created by a service builder for easy debugging.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log\LogPlugin;

    // Add a debug log plugin to every /* Replaced /* Replaced /* Replaced client */ */ */ as it is created
    $builder->addGlobalPlugin(LogPlugin::getDebugPlugin());

    $foo = $builder->get('foo');
    $foo->get('/')->send();
    // Should output all of the data sent over the wire

.. _service-builder-events:

Events emitted from a service builder
-------------------------------------

A ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder`` object emits the following events:

+-------------------------------+--------------------------------------------+-----------------------------------------+
| Event name                    | Description                                | Event data                              |
+===============================+============================================+=========================================+
| service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */ | Called when a /* Replaced /* Replaced /* Replaced client */ */ */ is created            | * /* Replaced /* Replaced /* Replaced client */ */ */: The created /* Replaced /* Replaced /* Replaced client */ */ */ object     |
+-------------------------------+--------------------------------------------+-----------------------------------------+

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;

    $builder = ServiceBuilder::factory('/path/to/config.json');

    // Add an event listener to print out each /* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced client */ */ */ as it is created
    $builder->getEventDispatcher()->addListener('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */', function (Event $e) {
        echo 'Client created: ' . get_class($e['/* Replaced /* Replaced /* Replaced client */ */ */']) . "\n";
    });

    $foo = $builder->get('foo');
    // Should output the class used for the "foo" /* Replaced /* Replaced /* Replaced client */ */ */
