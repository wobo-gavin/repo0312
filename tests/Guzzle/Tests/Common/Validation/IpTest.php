<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Ip
 */
class IpTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Ip';
        return array(
            array($c, 'a', null, 'Value is not a valid IP address', null),
            array($c, '192.168.16.121', null, true, null)
        );
    }
}
