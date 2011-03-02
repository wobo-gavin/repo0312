/* Replaced /* Replaced /* Replaced Guzzle */ */ */
======

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP framework for building REST webservice /* Replaced /* Replaced /* Replaced client */ */ */s.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides the tools necessary to quickly build a testable webservice /* Replaced /* Replaced /* Replaced client */ */ */ with complete control over preparing HTTP requests and processing HTTP responses.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ helps on the HTTP layer by allowing requests to be sent in parallel, automatically managing persistent cURL connections between requests for multiple hosts, and providing various pluggable behaviors for HTTP transactions (exponential backoff, over the wire logging, caching, cookies, etc...).

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ makes writing services an easy task by providing a simple pattern to follow:

#. Extend the default /* Replaced /* Replaced /* Replaced client */ */ */ class
#. Create a /* Replaced /* Replaced /* Replaced client */ */ */ builder if needed
#. Create commands for each API action.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses the command pattern.
#. Add the service definition to your services.xml file

Michael Dowling, lead developer of SHOEBACCA.com and the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ project, has written many PHP webservice /* Replaced /* Replaced /* Replaced client */ */ */s during his time at SHOEBACCA.com:

    | With the growth of our company came the growth of our website's feature set and the amount of web services we had to interact with.  We were consistently being tasked with integrating new web services.  Some of the services we integrated with had existing PHP /* Replaced /* Replaced /* Replaced client */ */ */s, but sadly, most PHP /* Replaced /* Replaced /* Replaced client */ */ */s seemed like more of an afterthough or were written in 2005.  After we created several /* Replaced /* Replaced /* Replaced client */ */ */s with very similar functionality, I realized the need for a webservice /* Replaced /* Replaced /* Replaced client */ */ */ framework that could reduce code duplication, make it dead simple to create a testable /* Replaced /* Replaced /* Replaced client */ */ */, and give developers access to a broad range of HTTP and webservice related functionality.
    |
    | Because of /* Replaced /* Replaced /* Replaced Guzzle */ */ */, now I don't cringe as much when my boss comes into my office telling me that we have a new API to integrate into our application.

Most web service /* Replaced /* Replaced /* Replaced client */ */ */s follow a specific pattern: create a /* Replaced /* Replaced /* Replaced client */ */ */ class, create methods for each action that can be taken on the API, create a cURL handle to transfer an HTTP request to the /* Replaced /* Replaced /* Replaced client */ */ */, parse the response, implement error handling, and return the result. You've probably had to interact with an API that either doesn't have a PHP /* Replaced /* Replaced /* Replaced client */ */ */ or the currently available PHP /* Replaced /* Replaced /* Replaced client */ */ */s are not up to an acceptable level of quality. When facing these types of situations, you probably find yourself writing a webservice that lacks most of the advanced features mentioned by Michael. It wouldn't make sense to spend all that time writing those features-- it's just a simple webservice /* Replaced /* Replaced /* Replaced client */ */ */ for just one API... But then you build another /* Replaced /* Replaced /* Replaced client */ */ */... and another. Suddenly you find yourself with several web service /* Replaced /* Replaced /* Replaced client */ */ */s to maintain, each /* Replaced /* Replaced /* Replaced client */ */ */ a God class, each reeking of code duplication and lacking most, if not all, of the aforementioned features. Enter /* Replaced /* Replaced /* Replaced Guzzle */ */ */.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is used in production a mutli-million dollar e-commerce company.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ has 100% code coverage; every line of /* Replaced /* Replaced /* Replaced Guzzle */ */ */ has been tested using PHPUnit.

Installing /* Replaced /* Replaced /* Replaced Guzzle */ */ */
-----------------

Contributors should install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ using git::

    git clone https://mtdowling@github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.git

Install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ using pear when using /* Replaced /* Replaced /* Replaced Guzzle */ */ */ in production::

    pear channel-discover pearhub.org
    pear install pearhub//* Replaced /* Replaced /* Replaced guzzle */ */ */

You will need to add /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to your application's autoloader.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ ships with a few select classes from other vendors, one of which is the Symfony2 universal class loader.  If your application does not already use an autoloader, you can use the autoloader distributed with /* Replaced /* Replaced /* Replaced Guzzle */ */ */:

.. code-block:: php

    <?php

    require_once '/path/to//* Replaced /* Replaced /* Replaced guzzle */ */ *//library/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

    $classLoader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
    $classLoader->registerNamespaces(array(
        '/* Replaced /* Replaced /* Replaced Guzzle */ */ */' => '/path/to//* Replaced /* Replaced /* Replaced guzzle */ */ *//library'
    ));
    $classLoader->register();

*Substitute '/path/to/' with the full path to your /* Replaced /* Replaced /* Replaced Guzzle */ */ */ installation.  You can find the PEAR installation folder using pear config-get php_dir*

Installing services
-------------------

Current Services
~~~~~~~~~~~~~~~~

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ services are distributed separately from the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ framework.  /* Replaced /* Replaced /* Replaced Guzzle */ */ */ officially supports a few webservice /* Replaced /* Replaced /* Replaced client */ */ */s (these /* Replaced /* Replaced /* Replaced client */ */ */s are currently what we use at SHOEBACCA.com), and hopefully there will be third-party created services coming soon:

* `Amazon Webservices (AWS) <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-aws>`_
    * Amazon S3
    * Amazon SimpleDB
    * Amazon SQS
    * Amazon MWS
* `Unfuddle <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-unfuddle>`_
* `Cardinal Commerce <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-cardinal-commerce>`_

When installing a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service, check the service's installation instructions for specific examples on how to install the service.  Most services can be installed using a git submodule or, if available, a PEAR package through pearhub.org::

    pear install pearhub//* Replaced /* Replaced /* Replaced guzzle */ */ */-aws # Note: this might not work while we're still finalizing our deployment methods

Services can also be installed using git submodules::

    git submodule add git://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-aws.git /path/to//* Replaced /* Replaced /* Replaced guzzle */ */ *//library//* Replaced /* Replaced /* Replaced Guzzle */ */ *//Service/Aws

*Please note: we are still figuring out a few deployment related issues, so the only packaged available on pearhub is /* Replaced /* Replaced /* Replaced guzzle */ */ */.*

Autoloading Services
~~~~~~~~~~~~~~~~~~~~

Services that are installed within the path to /* Replaced /* Replaced /* Replaced Guzzle */ */ */ under the Service folder will be autoloaded automatically using the autoloader settings configured for the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ library (e.g. //* Replaced /* Replaced /* Replaced Guzzle */ */ *//Service/Aws).  If you install a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service outside of this directory structure, you will need to add the service to the autoloader.

Using Services
--------------

Let's say you want to use the Amazon S3 /* Replaced /* Replaced /* Replaced client */ */ */ from the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ AWS service.

1. Create a services.xml file:

Create a services.xml that your ServiceBuilder will use to create service /* Replaced /* Replaced /* Replaced client */ */ */s.  The services.xml file defines the /* Replaced /* Replaced /* Replaced client */ */ */s you will be using and the arguments that will be passed into the /* Replaced /* Replaced /* Replaced client */ */ */ when it is constructed.  Each /* Replaced /* Replaced /* Replaced client */ */ */ + arguments combination is given a name and  referenced by name when retrieving a /* Replaced /* Replaced /* Replaced client */ */ */ from the ServiceBuilder.

.. code-block:: xml

    <?xml version="1.0" ?>
    </* Replaced /* Replaced /* Replaced guzzle */ */ */>
        </* Replaced /* Replaced /* Replaced client */ */ */s>
            <!-- Abstract service to store AWS account credentials -->
            </* Replaced /* Replaced /* Replaced client */ */ */ name="test.abstract.aws">
                <param name="access_key_id" value="12345" />
                <param name="secret_access_key" value="abcd" />
            <//* Replaced /* Replaced /* Replaced client */ */ */>
            </* Replaced /* Replaced /* Replaced client */ */ */ name="test.s3" builder="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Aws.S3.S3Builder" extends="test.abstract.aws">
                <param name="devpay_product_token" value="" />
                <param name="devpay_user_token" value="" />
            <//* Replaced /* Replaced /* Replaced client */ */ */>
        <//* Replaced /* Replaced /* Replaced client */ */ */s>
    <//* Replaced /* Replaced /* Replaced guzzle */ */ */>

2. Create a ServiceBuilder

.. code-block:: php

    <?php
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;

    $serviceBuilder = ServiceBuilder::factory('/path/to/services.xml');

3. Get the Amazon S3 /* Replaced /* Replaced /* Replaced client */ */ */ from the ServiceBuilder and execute a command

.. code-block:: php

    <?php
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = $serviceBuilder->getClient('test.s3');
    $command = new GetObject();
    $command->setBucket('mybucket')->setKey('mykey');

    // The result of the GetObject command returns the HTTP response object
    $httpResponse = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
    echo $httpResponse->getBody();

The GetObject command just returns the HTTP response object when it is executed.  Other commands might return more valuable information when executed:

.. code-block:: php

    <?php
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\ListBucket;

    $command = new ListBucket();
    $command->setBucket('mybucket');
    $objects = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

    // Iterate over every single object in the bucket
    // subsequent requests will be issued to retreive
    // the next result of a truncated response
    foreach ($objects as $object) {
        echo "{$object['key']} {$object['size']}\n";
    }

    // You can get access to the HTTP request issued by the command and the response
    echo $command->getRequest();
    echo $command->getResponse();

The ListBucket command above returns a BucketIterator which will iterate over the entire contents of a bucket.  As you can see, commands can be as simple or complex as you want.

If the above code samples seem a little verbose to you, you can take some shortcuts in your code by leveraging the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ command factory inherent to each /* Replaced /* Replaced /* Replaced client */ */ */:

.. code-block:: php

    <?php

    $objects = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('bucket.list_bucket', array('bucket' => 'my_bucket'))->execute();

## Examples of sending HTTP requests

### GET the google.com homepage

    <?php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;

    $request = RequestFactory::getInstance()->newRequest('GET', 'http://www.google.com/');
    $response = $message->send();

    echo $response->getStatusCode() . "\n";

    // Echo the raw HTTP request
    echo $request;

    // Echo the raw HTTP response
    echo $response;

### POST to a Solr server

    <?php

    $request = RequestFactory::getInstance()->newRequest('POST', 'http://localhost:8983/solr/update');
    $request->addPostFiles(array(
        'file' => '/path/to/documents.xml'
    ));
    $request->send();