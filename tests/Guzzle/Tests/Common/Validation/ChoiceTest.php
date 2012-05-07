<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Choice
 */
class ChoiceTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Choice';
        return array(
            array($c, 'foo', array('options' => array('foo', 'bar')), true, null),
            array($c, 'baz', array('options' => array('foo', 'bar')), 'Value must be one of: foo, bar', null)
        );
    }
}
