<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder
 */
class ServiceBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $arrayData = array(
        'michael.mock' => array(
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
            'params' => array(
                'username' => 'michael',
                'password' => 'testing123',
                'subdomain' => 'michael',
            ),
        ),
        'billy.mock' => array(
            'alias' => 'Hello!',
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
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
        ),
        'cache.adapter' => array(
            'class'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory',
            'params' => array(
                'cache.adapter'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter',
                'cache.provider' => 'Doctrine\Common\Cache\ArrayCache'
            )
        ),
        'service_uses_cache' => array(
            'class'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
            'params' => array(
                'cache'     => '{cache.adapter}',
                'username'  => 'foo',
                'password'  => 'bar',
                'subdomain' => 'baz'
            )
        )
    );

    public function testAllowsSerialization()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $cached = unserialize(serialize($builder));
        $this->assertEquals($cached, $builder);
    }

    public function testDelegatesFactoryMethodToAbstractFactory()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $c = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $c);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException
     * @expectedExceptionMessage No service is registered as foobar
     */
    public function testThrowsExceptionWhenGettingInvalidClient()
    {
        ServiceBuilder::factory($this->arrayData)->get('foobar');
    }

    public function testStoresClientCopy()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('http://127.0.0.1:8124/v1/michael', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $builder->get('michael.mock'));

        // Get another /* Replaced /* Replaced /* Replaced client */ */ */ but throw this one away
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = $builder->get('billy.mock', true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */2);
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

    public function testBuildersPassOptionsThroughToClients()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
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

    public function testUsesTheDefaultBuilderWhenNoBuilderIsSpecified()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
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
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $c);
    }

    public function testUsedAsArray()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $this->assertTrue($b->offsetExists('michael.mock'));
        $this->assertFalse($b->offsetExists('not_there'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client', $b['michael.mock']);

        unset($b['michael.mock']);
        $this->assertFalse($b->offsetExists('michael.mock'));

        $b['michael.mock'] = new Client('http://www.test.com/');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client', $b['michael.mock']);
    }

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

    public function testFactoryCanCreateFromArray()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $s = $b->get('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    public function testFactoryDoesNotRequireParams()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $s = $b->get('missing_params');
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    public function testBuilderAllowsReferencesBetweenClients()
    {
        $builder = ServiceBuilder::factory(array(
            'a' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
                'params' => array(
                    'other_/* Replaced /* Replaced /* Replaced client */ */ */' => '{b}',
                    'username'     => 'x',
                    'password'     => 'y',
                    'subdomain'    => 'z'
                )
            ),
            'b' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
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

    public function testEmitsEventsWhenClientsAreCreated()
    {
        // Ensure that the /* Replaced /* Replaced /* Replaced client */ */ */ signals that it emits an event
        $this->assertEquals(array('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */'), ServiceBuilder::getAllEvents());

        // Create a test service builder
        $builder = ServiceBuilder::factory(array(
            'a' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient',
                'params' => array(
                    'username'  => 'test',
                    'password'  => '123',
                    'subdomain' => 'z'
                )
            )
        ));

        // Add an event listener to pick up /* Replaced /* Replaced /* Replaced client */ */ */ creation events
        $emits = 0;
        $builder->getEventDispatcher()->addListener('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */', function($event) use (&$emits) {
            $emits++;
        });

        // Get the 'a' /* Replaced /* Replaced /* Replaced client */ */ */ by name
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('a');

        // Ensure that the event was emitted once, and that the /* Replaced /* Replaced /* Replaced client */ */ */ was present
        $this->assertEquals(1, $emits);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    public function testCanAddGlobalParametersToServicesOnLoad()
    {
        $builder = ServiceBuilder::factory($this->arrayData, array(
            'username'  => 'fred',
            'new_value' => 'test'
        ));

        $data = json_decode($builder->serialize(), true);

        foreach ($data as $service) {
            $this->assertEquals('fred', $service['params']['username']);
            $this->assertEquals('test', $service['params']['new_value']);
        }
    }

    public function testCacheServiceCanBeCreatedAndInjectedIntoOtherServices()
    {
        $builder = ServiceBuilder::factory($this->arrayData);
        $usesCache = $builder['service_uses_cache'];
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter', $usesCache->getConfig('cache'));
    }

    public function testServicesCanBeAddedToBuilderAfterInstantiationAndInjectedIntoServices()
    {
        // Grab the cache adapter and remove it from the config
        $cache = $this->arrayData['cache.adapter'];
        $data = $this->arrayData;
        unset($data['cache.adapter']);

        // Create the builder and add the cache adapter
        $builder = ServiceBuilder::factory($data);
        $builder['cache.adapter'] = $cache;

        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter',
            $builder['service_uses_cache']->getConfig('cache')
        );
    }

    public function testAddsGlobalPlugins()
    {
        $b = new ServiceBuilder($this->arrayData);
        $b->addGlobalPlugin(new HistoryPlugin());
        $s = $b->get('michael.mock');
        $this->assertTrue($s->getEventDispatcher()->hasListeners('request.complete'));
    }

    public function testCanGetData()
    {
        $b = new ServiceBuilder($this->arrayData);
        $this->assertEquals($this->arrayData['michael.mock'], $b->getData('michael.mock'));
        $this->assertNull($b->getData('ewofweoweofe'));
    }

    public function testCanGetByAlias()
    {
        $b = new ServiceBuilder($this->arrayData);
        $this->assertSame($b->get('billy.mock'), $b->get('Hello!'));
    }

    public function testCanOverwriteParametersForThrowawayClients()
    {
        $b = new ServiceBuilder($this->arrayData);

        $c1 = $b->get('michael.mock');
        $this->assertEquals('michael', $c1->getConfig('username'));

        $c2 = $b->get('michael.mock', array('username' => 'jeremy'));
        $this->assertEquals('jeremy', $c2->getConfig('username'));
    }

    public function testGettingAThrowawayClientWithParametersDoesNotAffectGettingOtherClients()
    {
        $b = new ServiceBuilder($this->arrayData);

        $c1 = $b->get('michael.mock', array('username' => 'jeremy'));
        $this->assertEquals('jeremy', $c1->getConfig('username'));

        $c2 = $b->get('michael.mock');
        $this->assertEquals('michael', $c2->getConfig('username'));
    }
}
