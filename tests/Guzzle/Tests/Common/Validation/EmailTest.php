<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Email
 */
class EmailTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Email';
        return array(
            array($c, 'a', null, 'Value is not a valid email address', null),
            array($c, 'a@example.com', null, true, null)
        );
    }
}
