<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Pool;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MockPool extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\Pool
{
    public function getHandle()
    {
        return $this->multiHandle;
    }
}