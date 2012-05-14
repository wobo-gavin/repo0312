<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

class ServiceBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $arrayData = array(
        'michael.mock' => array(
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
            'params' => array(
                'username' => 'michael',
                'password' => 'testing123',
                'subdomain' => 'michael',
            ),
        ),
        'billy.mock' => array(
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
            'params' => array(
                'username' => 'billy',
                'password' => 'passw0rd',
                'subdomain' => 'billy',
            ),
        ),
        'billy.testing' => array(
            'extends' => 'billy.mock',
            'params' => array(
                'subdomain' => 'test.billy',
            ),
        ),
        'missing_params' => array(
            'extends' => 'billy.mock'
        )
    );

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::serialize
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::unserialize
     */
    public function testAllowsSerialization()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $cached = unserialize(serialize($builder));
        $this->assertEquals($cached, $builder);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     */
    public function testDelegatesFactoryMethodToAbstractFactory()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $c = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $c);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::get
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException
     * @expectedExceptionMessage No service is registered as foobar
     */
    public function testThrowsExceptionWhenGettingInvalidClient()
    {
        ServiceBuilder::factory($this->arrayData)->get('foobar');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::get
     */
    public function testStoresClientCopy()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('http://127.0.0.1:8124/v1/michael', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $builder->get('michael.mock'));

        // Get another /* Replaced /* Replaced /* Replaced client */ */ */ but throw this one away
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = $builder->get('billy.mock', true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */2);
        $this->assertEquals('http://127.0.0.1:8124/v1/billy', $/* Replaced /* Replaced /* Replaced client */ */ */2->getBaseUrl());

        // Make sure the original /* Replaced /* Replaced /* Replaced client */ */ */ is still there and set
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */ === $builder->get('michael.mock'));

        // Create a new billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ that is stored
        $/* Replaced /* Replaced /* Replaced client */ */ */3 = $builder->get('billy.mock');

        // Make sure that the stored billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ is equal to the other stored /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */3 === $builder->get('billy.mock'));

        // Make sure that this /* Replaced /* Replaced /* Replaced client */ */ */ is not equal to the previous throwaway /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */2 === $builder->get('billy.mock'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder
     */
    public function testBuildersPassOptionsThroughToClients()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'subdomain' => 'michael',
                    'password' => 'test',
                    'username' => 'michael',
                    'curl.curlopt_proxyport' => 8080
                )
            )
        ));

        $c = $s->get('michael.mock');
        $this->assertEquals(8080, $c->getConfig('curl.curlopt_proxyport'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder
     */
    public function testUsesTheDefaultBuilderWhenNoBuilderIsSpecified()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'subdomain' => 'michael',
                    'password' => 'test',
                    'username' => 'michael',
                    'curl.curlopt_proxyport' => 8080
                )
            )
        ));

        $c = $s->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $c);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::set
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::offsetSet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::offsetGet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::offsetUnset
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::offsetExists
     */
    public function testUsedAsArray()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $this->assertTrue($b->offsetExists('michael.mock'));
        $this->assertFalse($b->offsetExists('not_there'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client', $b['michael.mock']);

        unset($b['michael.mock']);
        $this->assertFalse($b->offsetExists('michael.mock'));

        $b['michael.mock'] = new Client('http://www.test.com/');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client', $b['michael.mock']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     */
    public function testFactoryCanCreateFromJson()
    {
        $tmp = sys_get_temp_dir() . '/test.js';
        file_put_contents($tmp, json_encode($this->arrayData));
        $b = ServiceBuilder::factory($tmp);
        unlink($tmp);
        $s = $b->get('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     */
    public function testFactoryCanCreateFromArray()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $s = $b->get('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Unable to build service builder
     */
    public function testFactoryValidatesFileExtension()
    {
        $tmp = sys_get_temp_dir() . '/test.abc';
        file_put_contents($tmp, 'data');
        try {
            ServiceBuilder::factory($tmp);
        } catch (\RuntimeException $e) {
            unlink($tmp);
            throw $e;
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Must pass a file name, array, or SimpleXMLElement
     */
    public function testFactoryValidatesObjectTypes()
    {
        ServiceBuilder::factory(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     */
    public function testFactoryDoesNotRequireParams()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $s = $b->get('missing_params');
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder
     */
    public function testBuilderAllowsReferencesBetweenClients()
    {
        $builder = ServiceBuilder::factory(array(
            'a' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'other_/* Replaced /* Replaced /* Replaced client */ */ */' => '{{ b }}',
                    'username'     => 'x',
                    'password'     => 'y',
                    'subdomain'    => 'z'
                )
            ),
            'b' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'username'  => '1',
                    'password'  => '2',
                    'subdomain' => '3'
                )
            )
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder['a'];
        $this->assertEquals('x', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('username'));
        $this->assertSame($builder['b'], $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('other_/* Replaced /* Replaced /* Replaced client */ */ */'));
        $this->assertEquals('1', $builder['b']->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::getAllEvents
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::get
     */
    public function testEmitsEventsWhenClientsAreCreated()
    {
        // Ensure that the /* Replaced /* Replaced /* Replaced client */ */ */ signals that it emits an event
        $this->assertEquals(array('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */'), ServiceBuilder::getAllEvents());

        // Create a test service builder
        $builder = ServiceBuilder::factory(array(
            'a' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'username'  => 'test',
                    'password'  => '123',
                    'subdomain' => 'z'
                )
            )
        ));

        $emits = 0;
        $emitted = null;

        // Add an event listener to pick up /* Replaced /* Replaced /* Replaced client */ */ */ creation events
        $builder->getEventDispatcher()->addListener('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */', function($event) use (&$emits, &$emitted) {
            $emits++;
            $emitted = $event['/* Replaced /* Replaced /* Replaced client */ */ */'];
        });

        // Get the 'a' /* Replaced /* Replaced /* Replaced client */ */ */ by name
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('a');

        // Ensure that the event was emitted once, and that the /* Replaced /* Replaced /* Replaced client */ */ */ was present
        $this->assertEquals(1, $emits);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory
     */
    public function testCanAddGlobalParametersToServicesOnLoad()
    {
        $builder = ServiceBuilder::factory($this->arrayData, array(
            'username' => 'fred',
            'new_value' => 'test'
        ));

        $data = json_decode($builder->serialize(), true);

        foreach ($data as $service) {
            $this->assertEquals('fred', $service['params']['username']);
            $this->assertEquals('test', $service['params']['new_value']);
        }
    }

    public function testDescriptionIsCacheable()
    {
        $jsonFile = __DIR__ . '/../../TestData/test_service.json';
        $adapter = new DoctrineCacheAdapter(new ArrayCache());

        $builder = ServiceBuilder::factory($jsonFile, array(
            'cache.adapter' => $adapter
        ));

        // Ensure the cache key was set
        $this->assertTrue($adapter->contains('/* Replaced /* Replaced /* Replaced guzzle */ */ */' . crc32($jsonFile)));

        // Grab the service from the cache
        $this->assertEquals($builder, ServiceBuilder::factory($jsonFile, array(
            'cache.adapter' => $adapter
        )));
    }
}
