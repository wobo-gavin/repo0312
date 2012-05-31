<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
 */
class InspectorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::setTypeValidation
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getTypeValidation
     */
    public function testTypeValidationCanBeToggled()
    {
        $i = new Inspector();
        $this->assertTrue($i->getTypeValidation());
        $i->setTypeValidation(false);
        $this->assertFalse($i->getTypeValidation());
    }

    /**
     * @cover /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::__constructor
     */
    public function testRegistersDefaultFilters()
    {
        $inspector = new Inspector();
        $this->assertNotEmpty($inspector->getRegisteredConstraints());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
     * @expectedException InvalidArgumentException
     */
    public function testChecksFilterValidity()
    {
        Inspector::getInstance()->getConstraint('foooo');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::prepareConfig
     */
    public function testPreparesConfig()
    {
        $c = Inspector::prepareConfig(array(
            'a' => '123',
            'base_url' => 'http://www.test.com/'
        ), array(
            'a' => 'xyz',
            'b' => 'lol'
        ), array('a'));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $c);
        $this->assertEquals(array(
            'a' => '123',
            'b' => 'lol',
            'base_url' => 'http://www.test.com/'
        ), $c->getAll());

        try {
            $c = Inspector::prepareConfig(null, null, array('a'));
            $this->fail('Exception not throw when missing config');
        } catch (ValidationException $e) {
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::registerConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getRegisteredConstraints
     */
    public function testRegistersCustomConstraints()
    {
        $constraintClass = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Validation\\Ip';

        Inspector::getInstance()->registerConstraint('mock', $constraintClass);
        Inspector::getInstance()->registerConstraint('mock_2', $constraintClass, array(
           'version' => '4'
        ));

        $this->assertArrayHasKey('mock', Inspector::getInstance()->getRegisteredConstraints());
        $this->assertArrayHasKey('mock_2', Inspector::getInstance()->getRegisteredConstraints());

        $this->assertInstanceOf($constraintClass, Inspector::getInstance()->getConstraint('mock'));
        $this->assertInstanceOf($constraintClass, Inspector::getInstance()->getConstraint('mock_2'));

        $this->assertTrue(Inspector::getInstance()->validateConstraint('mock', '192.168.16.121'));
        $this->assertTrue(Inspector::getInstance()->validateConstraint('mock_2', '10.1.1.0'));
    }
}
