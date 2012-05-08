<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Regex
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AbstractConstraint
 */
class RegexTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\Regex';
        return array(
            array($c, 'foo', array('pattern' => '/[a-z]+/'), true, null),
            array($c, 'foo', array('/[a-z]+/'), true, null),
            array($c, 'foo', array('pattern' => '/[0-9]+/'), 'foo does not match the regular expression', null),
            array($c, 'baz', null, null, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException')
        );
    }
}
