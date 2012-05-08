<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\String
 */
class StringTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\String';
        return array(
            array($c, 'foo', null, true, null),
            array($c, new \stdClass(), null, 'Value must be a string', null),
            array($c, false, null, 'Value must be a string', null)
        );
    }
}
