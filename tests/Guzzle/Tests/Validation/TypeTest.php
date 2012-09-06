<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Type
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\AbstractConstraint
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\AbstractType
 */
class TypeTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Type';
        return array(
            array($c, 'a', array('type' => 'string'), true, null),
            array($c, 'a', array('string'), true, null),
            array($c, '2', array('type' => 'array'), 'Value must be of type array', null)
        );
    }
}
