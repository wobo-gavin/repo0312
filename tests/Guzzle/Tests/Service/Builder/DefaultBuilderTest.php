<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use Doctrine\Common\Cache\ArrayCache;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder;

/**
 * @group Builder
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DefaultBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\AbstractBuilder::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::getConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\AbstractBuilder::getName
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\AbstractBuilder::setName
     */
    public function testConstructor()
    {
        $builder = new DefaultBuilder(array(
            'key' => 'value'
        ), 'test');

        // Test the name of the builder
        $this->assertEquals('test', $builder->getName());
        $this->assertEquals($builder, $builder->setName('whodat'));
        $this->assertEquals('whodat', $builder->getName());

        $this->assertEquals(array('key' => 'value'), $builder->getConfig()->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\AbstractBuilder::setCache
     */
    public function testHasCache()
    {
        $builder = new DefaultBuilder(array(
            'key' => 'value'
        ), 'test');

        $cacheAdapter = new DoctrineCacheAdapter(new ArrayCache());

        // Test the name of the builder
        $this->assertSame($builder, $builder->setCache($cacheAdapter, 1234));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::getClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::setClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::build
     */
    public function testDefaultBuilderHasClass()
    {
        $builder = new DefaultBuilder(array(
            'key' => 'value'
        ), 'test');

        try {
            $builder->build();
            $this->fail('Exception not thrown when building without a class when using the default builder');
        } catch (\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException $e) {}

        $this->assertEquals($builder, $builder->setClass('abc.123'));

        // The builder will convert lowercase and periods
        $this->assertEquals('Abc\\123', $builder->getClass());

        try {
            $builder->build();
            $this->fail('Exception not thrown when building with an invalid class');
        } catch (\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException $e) {}
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::build
     */
    public function testBuildsClients()
    {
        $builder = new DefaultBuilder(array(
            'username' => 'michael',
            'password' => 'test',
            'subdomain' => 'michael'
        ), 'michael.unfuddle');

        $builder->setClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);

        // make sure a service was created correctly
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getService()->hasCommand('sub.sub'));
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getService()->hasCommand('mock_command'));
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getService()->hasCommand('other_command'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\AbstractBuilder::__toString
     */
    public function testConvertsToXmlString()
    {
        $builder = new DefaultBuilder(array(
            'username' => 'michael',
            'password' => 'test',
            'subdomain' => 'michael'
        ), 'mock');

        $builder->setClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient');

        $xml = <<<EOT
</* Replaced /* Replaced /* Replaced client */ */ */ name="mock" class="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Tests.Service.Mock.MockClient">
    <param name="username" value="michael" />
    <param name="password" value="test" />
    <param name="subdomain" value="michael" />
<//* Replaced /* Replaced /* Replaced client */ */ */>
EOT;
        $xml = trim($xml);

        $this->assertEquals($xml, (string) $builder);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder
     */
    public function testUsesCache()
    {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);
        $this->assertEmpty($cache->getIds());
        $builder = new DefaultBuilder(array(
            'username' => 'michael',
            'password' => 'test',
            'subdomain' => 'michael'
        ), 'michael.unfuddle');

        $builder->setClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient');
        $this->assertSame($builder, $builder->setCache($adapter));

        $/* Replaced /* Replaced /* Replaced client */ */ */1 = $builder->build();

        $this->assertNotEmpty($cache->getIds());

        $/* Replaced /* Replaced /* Replaced client */ */ */2 = $builder->build();
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */1, $/* Replaced /* Replaced /* Replaced client */ */ */2);
        $this->assertNotNull($/* Replaced /* Replaced /* Replaced client */ */ */2->getConfig('_service_from_cache'));
    }
}