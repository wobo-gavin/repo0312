<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory;

class CompositeFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private function getFactory($class = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\MapFactory')
    {
        return $mock = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::getIterator
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::count
     */
    public function testIsIterable()
    {
        $factory = new CompositeFactory(array($this->getFactory(), $this->getFactory()));
        $this->assertEquals(2, count($factory));
        $this->assertEquals(2, count(iterator_to_array($factory->getIterator())));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::find
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::has
     */
    public function testFindsFactories()
    {
        $f1 = $this->getFactory();
        $f2 = $this->getFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $factory = new CompositeFactory(array($f1, $f2));
        $this->assertNull($factory->find('foo'));
        $this->assertNull($factory->find($this->getFactory()));
        $this->assertSame($f1, $factory->find('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\MapFactory'));
        $this->assertSame($f2, $factory->find('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory'));
        $this->assertSame($f1, $factory->find($f1));
        $this->assertSame($f2, $factory->find($f2));

        $this->assertFalse($factory->has('foo'));
        $this->assertTrue($factory->has('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\MapFactory'));
        $this->assertTrue($factory->has('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::__construct
     */
    public function testCreatesCommands()
    {
        $factory = new CompositeFactory();
        $this->assertNull($factory->factory('foo'));

        $f1 = $this->getFactory();
        $mockCommand1 = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\AbstractCommand');

        $f1->expects($this->once())
           ->method('factory')
           ->with($this->equalTo('foo'))
           ->will($this->returnValue($mockCommand1));

        $factory = new CompositeFactory(array($f1));
        $this->assertSame($mockCommand1, $factory->factory('foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::remove
     */
    public function testAllowsRemovalOfFactories()
    {
        $f1 = $this->getFactory();
        $f2 = $this->getFactory();
        $f3 = $this->getFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $factories = array($f1, $f2, $f3);
        $factory = new CompositeFactory($factories);

        $factory->remove('foo');
        $this->assertEquals($factories, $factory->getIterator()->getArrayCopy());

        $factory->remove($f1);
        $this->assertEquals(array($f2, $f3), $factory->getIterator()->getArrayCopy());

        $factory->remove('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\MapFactory');
        $this->assertEquals(array($f3), $factory->getIterator()->getArrayCopy());

        $factory->remove('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $this->assertEquals(array(), $factory->getIterator()->getArrayCopy());

        $factory->remove('foo');
        $this->assertEquals(array(), $factory->getIterator()->getArrayCopy());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::add
     */
    public function testAddsFactoriesBeforeAndAtEnd()
    {
        $f1 = $this->getFactory();
        $f2 = $this->getFactory();
        $f3 = $this->getFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $f4 = $this->getFactory();

        $factory = new CompositeFactory();

        $factory->add($f1);
        $this->assertEquals(array($f1), $factory->getIterator()->getArrayCopy());

        $factory->add($f2);
        $this->assertEquals(array($f1, $f2), $factory->getIterator()->getArrayCopy());

        $factory->add($f3, $f2);
        $this->assertEquals(array($f1, $f3, $f2), $factory->getIterator()->getArrayCopy());

        $factory->add($f4, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $this->assertEquals(array($f1, $f4, $f3, $f2), $factory->getIterator()->getArrayCopy());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory::getDefaultChain
     */
    public function testProvidesDefaultChainForClients()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client');
        $chain = CompositeFactory::getDefaultChain($/* Replaced /* Replaced /* Replaced client */ */ */);
        $a = $chain->getIterator()->getArrayCopy();
        $this->assertEquals(1, count($a));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ConcreteClassFactory', $a[0]);

        $description = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $/* Replaced /* Replaced /* Replaced client */ */ */->expects($this->once())
               ->method('getDescription')
               ->will($this->returnValue($description));
        $chain = CompositeFactory::getDefaultChain($/* Replaced /* Replaced /* Replaced client */ */ */);
        $a = $chain->getIterator()->getArrayCopy();
        $this->assertEquals(2, count($a));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ServiceDescriptionFactory', $a[0]);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ConcreteClassFactory', $a[1]);
    }
}