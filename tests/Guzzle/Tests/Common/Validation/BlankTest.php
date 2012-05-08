<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Blank
 */
class BlankTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Blank';
        return array(
            array($c, '', null, true, null),
            array($c, null, null, true, null),
            array($c, false, null, 'Value must be blank', null),
            array($c, 'abc', null, 'Value must be blank', null)
        );
    }
}
