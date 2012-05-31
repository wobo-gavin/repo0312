<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException;

class ValidationExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCanSetAndRetrieveErrors()
    {
        $errors = array('foo', 'bar');

        $e = new ValidationException('Foo');
        $e->setErrors($errors);
        $this->assertEquals($errors, $e->getErrors());
    }
}
