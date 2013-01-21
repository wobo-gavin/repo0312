<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Resource;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\CompositeResourceIteratorFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\ResourceIteratorClassFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\CompositeResourceIteratorFactory
 */
class CompositeResourceIteratorFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Iterator was not found for mock_command
     */
    public function testEnsuresIteratorClassExists()
    {
        $factory = new CompositeResourceIteratorFactory(array(
            new ResourceIteratorClassFactory(array('Foo', 'Bar'))
        ));
        $cmd = new MockCommand();
        $this->assertFalse($factory->canBuild($cmd));
        $factory->build($cmd);
    }

    public function testBuildsResourceIterators()
    {
        $f1 = new ResourceIteratorClassFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model');
        $factory = new CompositeResourceIteratorFactory(array());
        $factory->addFactory($f1);
        $command = new MockCommand();
        $iterator = $factory->build($command, array('/* Replaced /* Replaced /* Replaced client */ */ */.namespace' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }
}
