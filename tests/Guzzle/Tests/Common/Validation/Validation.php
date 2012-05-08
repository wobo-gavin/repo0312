<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

class Validation extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @dataProvider provider
     */
    public function testConstraint($constraint, $value, $options = null, $result = null, $exception = null)
    {
        $c = new $constraint();
        try {
            $r = $c->validate($value, $options);
            $this->assertEquals($result, $r);
        } catch (\Exception $e) {
            if (!$exception) {
                throw $e;
            }

            if (!($e instanceof $exception)) {
                throw $e;
            }
        }
    }
}
