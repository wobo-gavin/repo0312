<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Ctype
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AbstractConstraint
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AbstractType
 */
class CtypeTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Ctype';
        return array(
            array($c, 'a', array('type' => 'alpha'), true, null),
            array($c, 'a', array('alpha'), true, null),
            array($c, '2', array('type' => 'alpha'), 'Value must be of type alpha', null),
            array($c, ' ', array('type' => 'space'), true, null),
            array($c, 'a', array('foo'), null, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException')
        );
    }
}
