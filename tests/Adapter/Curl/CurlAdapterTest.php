<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter\Curl;

require_once __DIR__ . '/AbstractCurl.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter
 */
class CurlAdapterTest extends AbstractCurl
{
    protected function setUp()
    {
        if (!function_exists('curl_reset')) {
            $this->markTestSkipped('curl_reset() is not available');
        }
    }

    protected function getAdapter($factory = null, $options = [])
    {
        return new CurlAdapter($factory ?: new MessageFactory(), $options);
    }

    public function canSetMaxHandles()
    {
        $a = new CurlAdapter(new MessageFactory(), ['max_handles' => 10]);
        $this->assertEquals(10, $this->readAttribute($a, 'maxHandles'));
    }
}
