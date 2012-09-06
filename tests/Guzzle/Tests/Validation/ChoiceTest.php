<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Choice
 */
class ChoiceTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Choice';
        return array(
            array($c, 'foo', array('options' => array('foo', 'bar')), true, null),
            array($c, 'baz', array('options' => array('foo', 'bar')), 'Value must be one of: foo, bar', null)
        );
    }
}
