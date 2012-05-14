<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\AbstractFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException;
use Doctrine\Common\Cache\ArrayCache;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\AbstractFactory
 */
class AbstractFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getFactory()
    {
        return $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\AbstractFactory')
            ->setMethods(array('getCacheTtlKey', 'throwException', 'getClassName'))
            ->getMockForAbstractClass();
    }

    public function testCachesArtifacts()
    {
        $jsonFile = __DIR__ . '/../TestData/test_service.json';

        $adapter = new DoctrineCacheAdapter(new ArrayCache());
        $factory = $this->getFactory();

        $factory->expects($this->once())
            ->method('getClassName')
            ->will($this->returnValue('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\JsonServiceBuilderFactory'));

        // Create a service and add it to the cache
        $service = $factory->build($jsonFile, array(
            'cache.adapter' => $adapter
        ));

        // Ensure the cache key was set
        $this->assertTrue($adapter->contains('/* Replaced /* Replaced /* Replaced guzzle */ */ */' . crc32($jsonFile)));

        // Grab the service from the cache
        $this->assertEquals($service, $factory->build($jsonFile, array(
            'cache.adapter' => $adapter
        )));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     */
    public function testThrowsExceptionsWhenNoFactoryResolves()
    {
        $factory = $this->getFactory();
        $factory->expects($this->any())
            ->method('getClassName')
            ->will($this->returnValue(false));

        // Exceptions are mocked and disabled, so nothing happens here
        $service = $factory->build('foo');

        // Throw an exception when it's supposed to
        $factory->expects($this->any())
            ->method('throwException')
            ->will($this->throwException(new ServiceBuilderException()));

        $service = $factory->build('foo');
    }
}
