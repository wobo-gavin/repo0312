<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Validation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\IsInstanceOf;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\IsInstanceOf
 */
class IsInstanceOfTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\IsInstanceOf';
        return array(
            array($c, new \DateTime(), array('class' => 'stdClass'), 'Value must be an instance of stdClass', null),
            array($c, new \stdClass(), array('class' => 'stdClass'), true, null),
            array($c, new IsInstanceOf(), array('class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Validation.IsInstanceOf'), true, null),
            array($c, 'a', null, true, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException'),
            array($c, new \stdClass(), null, true, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException')
        );
    }
}
