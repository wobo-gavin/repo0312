<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\NotBlank
 */
class NotBlankTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\NotBlank';
        return array(
            array($c, 'foo', null, true, null),
            array($c, null, null, 'Value must not be blank', null),
            array($c, '', null, 'Value must not be blank', null)
        );
    }
}
