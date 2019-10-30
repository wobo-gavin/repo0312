<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\Proxy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RequestOptions;
use PHPUnit\Framework\TestCase;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\Proxy
 */
class ProxyTest extends TestCase
{
    public function testSendsToNonSync()
    {
        $a = $b = null;
        $m1 = new MockHandler([function ($v) use (&$a) {
            $a = $v;
        }]);
        $m2 = new MockHandler([function ($v) use (&$b) {
            $b = $v;
        }]);
        $h = Proxy::wrapSync($m1, $m2);
        $h(new Request('GET', 'http://foo.com'), []);
        $this->assertNotNull($a);
        $this->assertNull($b);
    }

    public function testSendsToSync()
    {
        $a = $b = null;
        $m1 = new MockHandler([function ($v) use (&$a) {
            $a = $v;
        }]);
        $m2 = new MockHandler([function ($v) use (&$b) {
            $b = $v;
        }]);
        $h = Proxy::wrapSync($m1, $m2);
        $h(new Request('GET', 'http://foo.com'), [RequestOptions::SYNCHRONOUS => true]);
        $this->assertNull($a);
        $this->assertNotNull($b);
    }

    public function testSendsToStreaming()
    {
        $a = $b = null;
        $m1 = new MockHandler([function ($v) use (&$a) {
            $a = $v;
        }]);
        $m2 = new MockHandler([function ($v) use (&$b) {
            $b = $v;
        }]);
        $h = Proxy::wrapStreaming($m1, $m2);
        $h(new Request('GET', 'http://foo.com'), []);
        $this->assertNotNull($a);
        $this->assertNull($b);
    }

    public function testSendsToNonStreaming()
    {
        $a = $b = null;
        $m1 = new MockHandler([function ($v) use (&$a) {
            $a = $v;
        }]);
        $m2 = new MockHandler([function ($v) use (&$b) {
            $b = $v;
        }]);
        $h = Proxy::wrapStreaming($m1, $m2);
        $h(new Request('GET', 'http://foo.com'), ['stream' => true]);
        $this->assertNull($a);
        $this->assertNotNull($b);
    }
}
