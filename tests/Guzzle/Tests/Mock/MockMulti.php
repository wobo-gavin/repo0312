<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock;

class MockMulti extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
{
    public function getHandle()
    {
        return $this->multiHandle;
    }
}
