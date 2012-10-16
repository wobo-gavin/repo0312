<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Resource;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\MapResourceIteratorFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\MapResourceIteratorFactory
 */
class MapResourceIteratorFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Iterator was not found for mock_command
     */
    public function testEnsuresIteratorClassExists()
    {
        $factory = new MapResourceIteratorFactory(array('Foo', 'Bar'));
        $factory->build(new MockCommand());
    }

    public function testBuildsResourceIterators()
    {
        $factory = new MapResourceIteratorFactory(array(
            'mock_command' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator'
        ));
        $iterator = $factory->build(new MockCommand());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }

    public function testUsesWildcardMappings()
    {
        $factory = new MapResourceIteratorFactory(array(
            '*' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator'
        ));
        $iterator = $factory->build(new MockCommand());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }
}
