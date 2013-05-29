<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header
 */
class HeaderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $test = array(
        'zoo'   => array('foo', 'Foo'),
        'Zoo'   => 'bar',
    );

    public function testStoresHeaderName()
    {
        $i = new Header('Zoo', $this->test);
        $this->assertEquals('Zoo', $i->getName());
    }

    public function testConvertsToString()
    {
        $i = new Header('Zoo', $this->test);
        $this->assertEquals('foo, Foo, bar', (string) $i);
        $i->setGlue(';');
        $this->assertEquals('foo; Foo; bar', (string) $i);
    }

    public function testNormalizesGluedHeaders()
    {
        $h = new Header('Zoo', array('foo, Faz', 'bar'));
        $result = $h->normalize(true)->toArray();
        natsort($result);
        $this->assertEquals(array('bar', 'foo', 'Faz'), $result);
    }

    public function testCanSearchForValues()
    {
        $h = new Header('Zoo', $this->test);
        $this->assertTrue($h->hasValue('foo'));
        $this->assertTrue($h->hasValue('Foo'));
        $this->assertTrue($h->hasValue('bar'));
        $this->assertFalse($h->hasValue('moo'));
        $this->assertFalse($h->hasValue('FoO'));
    }

    public function testIsCountable()
    {
        $h = new Header('Zoo', $this->test);
        $this->assertEquals(3, count($h));
    }

    public function testCanBeIterated()
    {
        $h = new Header('Zoo', $this->test);
        $results = array();
        foreach ($h as $key => $value) {
            $results[$key] = $value;
        }
        $this->assertEquals(array(
            'foo', 'Foo', 'bar'
        ), $results);
    }

    public function testAllowsFalseyValues()
    {
        // Allows 0
        $h = new Header('Foo', 0, ';');
        $this->assertEquals('0', (string) $h);
        $this->assertEquals(1, count($h));
        $this->assertEquals(';', $h->getGlue());

        // Does not add a null header by default
        $h = new Header('Foo');
        $this->assertEquals('', (string) $h);
        $this->assertEquals(0, count($h));

        // Allows null array for a single null header
        $h = new Header('Foo', array(null));
        $this->assertEquals('', (string) $h);

        // Allows empty string
        $h = new Header('Foo', '');
        $this->assertEquals('', (string) $h);
        $this->assertEquals(1, count($h));
    }

    public function testCanCheckForExactHeaderValues()
    {
        $h = new Header('Foo', 'bar', ';');
        $this->assertTrue($h->hasExactHeader('Foo'));
        $this->assertFalse($h->hasExactHeader('foo'));
    }

    public function testCanRemoveValues()
    {
        $h = new Header('Foo', array('Foo', 'baz', 'bar'));
        $h->removeValue('bar');
        $this->assertTrue($h->hasValue('Foo'));
        $this->assertFalse($h->hasValue('bar'));
        $this->assertTrue($h->hasValue('baz'));
    }

    public function testAllowsArrayInConstructor()
    {
        $h = new Header('Foo', array('Testing', '123', 'Foo=baz'));
        $this->assertEquals(array('Testing', '123', 'Foo=baz'), $h->toArray());
    }

    public function parseParamsProvider()
    {
        $res1 = array(
            array(
                '<http:/.../front.jpeg>' => '',
                'rel' => 'front',
                'type' => 'image/jpeg',
            ),
            array(
                '<http://.../back.jpeg>' => '',
                'rel' => 'back',
                'type' => 'image/jpeg',
            ),
        );

        return array(
            array(
                '<http:/.../front.jpeg>; rel="front"; type="image/jpeg", <http://.../back.jpeg>; rel=back; type="image/jpeg"',
                $res1
            ),
            array(
                '<http:/.../front.jpeg>; rel="front"; type="image/jpeg",<http://.../back.jpeg>; rel=back; type="image/jpeg"',
                $res1
            ),
            array(
                'foo="baz"; bar=123, boo, test="123"',
                array(
                    array('foo' => 'baz', 'bar' => '123'),
                    array('boo' => ''),
                    array('test' => '123')
                )
            )
        );
    }

    /**
     * @dataProvider parseParamsProvider
     */
    public function testParseParams($header, $result)
    {
        $response = new Response(200, array('Link' => $header));
        $this->assertEquals($result, $response->getHeader('Link')->parseParams());
    }
}
