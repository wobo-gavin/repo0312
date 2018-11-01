<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\TransferStats;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use PHPUnit\Framework\TestCase;

class TransferStatsTest extends TestCase
{
    public function testHasData()
    {
        $request = new /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request('GET', 'http://foo.com');
        $response = new /* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response();
        $stats = new TransferStats(
            $request,
            $response,
            10.5,
            null,
            ['foo' => 'bar']
        );
        $this->assertSame($request, $stats->getRequest());
        $this->assertSame($response, $stats->getResponse());
        $this->assertTrue($stats->hasResponse());
        $this->assertSame(['foo' => 'bar'], $stats->getHandlerStats());
        $this->assertSame('bar', $stats->getHandlerStat('foo'));
        $this->assertSame($request->getUri(), $stats->getEffectiveUri());
        $this->assertEquals(10.5, $stats->getTransferTime());
        $this->assertNull($stats->getHandlerErrorData());
    }
}
