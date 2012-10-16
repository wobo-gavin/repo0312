<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DenyRevalidation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DenyRevalidation
 */
class DenyRevalidationTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testDeniesRequestRevalidation()
    {
        $deny = new DenyRevalidation();
        $this->assertFalse($deny->revalidate(new Request('GET', 'http://foo.com'), new Response(200)));
    }
}
