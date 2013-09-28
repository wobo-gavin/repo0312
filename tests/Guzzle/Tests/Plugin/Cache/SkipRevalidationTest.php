<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\SkipRevalidation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\SkipRevalidation
 */
class SkipRevalidationTest extends \PHPUnit_Framework_TestCase
{
    public function testSkipsRequestRevalidation()
    {
        $skip = new SkipRevalidation();
        $this->assertTrue($skip->revalidate(new Request('GET', 'http://foo.com'), new Response(200)));
    }
}
