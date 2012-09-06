<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Numeric
 */
class NumericTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Numeric';
        return array(
            array($c, '123', null, true, null),
            array($c, 123, null, true, null),
            array($c, '-10', null, true, null),
            array($c, 'abc', null, 'Value must be numeric', null)
        );
    }
}
