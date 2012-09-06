<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Resource;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\ResourceIteratorClassFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\ResourceIteratorClassFactory
 */
class ResourceIteratorClassFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The first argument must be an instance of CommandInterface
     */
    public function testValidatesCommand()
    {
        $factory = new ResourceIteratorClassFactory();
        $factory->build('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Iterator was not found matching MockCommandIterator
     */
    public function testEnsuresIteratorClassExists()
    {
        $factory = new ResourceIteratorClassFactory(array('Foo', 'Bar'));
        $factory->registerNamespace('Baz');
        $command = new MockCommand();
        $iterator = $factory->build($command);
    }

    public function testBuildsResourceIterators()
    {
        $factory = new ResourceIteratorClassFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model');
        $command = new MockCommand();
        $iterator = $factory->build($command, array(
            '/* Replaced /* Replaced /* Replaced client */ */ */.namespace' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }
}
