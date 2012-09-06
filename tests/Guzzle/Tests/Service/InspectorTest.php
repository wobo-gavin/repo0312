<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::registerConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getRegisteredConstraints
     */
    public function testRegistersCustomConstraints()
    {
        $constraintClass = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Ip';

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
