<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * @group server
 */
class EntityBodyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testFactoryThrowsException()
    {
        $body = EntityBody::factory(false);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::fromString
     */
    public function testFactory()
    {
        $body = EntityBody::factory('data');
        $this->assertEquals('data', (string)$body);
        $this->assertEquals(4, $body->getContentLength());
        $this->assertEquals('php', $body->getWrapper());
        $this->assertEquals('temp', $body->getStreamType());

        $handle = fopen(__DIR__ . '/../../../../phpunit.xml.dist', 'r');
        if (!$handle) {
            $this->fail('Could not open test file');
        }
        $body = EntityBody::factory($handle);
        $this->assertEquals(__DIR__ . '/../../../../phpunit.xml.dist', $body->getUri());
        $this->assertTrue($body->isLocal());
        $this->assertEquals(__DIR__ . '/../../../../phpunit.xml.dist', $body->getUri());
        $this->assertEquals(filesize(__DIR__ . '/../../../../phpunit.xml.dist'), $body->getContentLength());

        // make sure that a body will return as the same object
        $this->assertTrue($body === EntityBody::factory($body));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     */
    public function testFactoryCreatesTempStreamByDefault()
    {
        $body = EntityBody::factory('');
        $this->assertEquals('php', $body->getWrapper());
        $this->assertEquals('temp', $body->getStreamType());
        $body = EntityBody::factory();
        $this->assertEquals('php', $body->getWrapper());
        $this->assertEquals('temp', $body->getStreamType());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     */
    public function testFactoryCanCreateFromObject()
    {
        $body = EntityBody::factory(new QueryString(array('foo' => 'bar')));
        $this->assertEquals('?foo=bar', (string) $body);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testFactoryEnsuresObjectsHaveToStringMethod()
    {
        EntityBody::factory(new \stdClass('a'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::compress
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::uncompress
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::getContentEncoding
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::setStreamFilterContentEncoding
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::handleCompression
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::getContentLength
     */
    public function testHandlesCompression()
    {
        $body = EntityBody::factory('testing 123...testing 123');
        $this->assertFalse($body->getContentEncoding(), '-> getContentEncoding() must initially return FALSE');
        $size = $body->getContentLength();
        $body->compress();
        $this->assertEquals('gzip', $body->getContentEncoding(), '-> getContentEncoding() must return the correct encoding after compressing');
        $this->assertEquals(gzdeflate('testing 123...testing 123'), (string) $body);
        $this->assertTrue($body->getContentLength() < $size);
        $this->assertTrue($body->uncompress());
        $this->assertEquals('testing 123...testing 123', (string) $body);
        $this->assertFalse($body->getContentEncoding(), '-> getContentEncoding() must reset to FALSE');

        if (in_array('bzip2.*', stream_get_filters())) {
            $this->assertTrue($body->compress('bzip2.compress'));
            $this->assertEquals('compress', $body->getContentEncoding(), '-> compress() must set \'compress\' as the Content-Encoding');
        }

        $this->assertFalse($body->compress('non-existent'), '-> compress() must return false when a non-existent stream filter is used');

        // Release the body
        unset($body);

        // Use gzip compression on the initial content.  This will include a
        // gzip header which will need to be stripped when deflating the stream
        $body = EntityBody::factory(gzencode('test'));
        $this->assertSame($body, $body->setStreamFilterContentEncoding('zlib.deflate'));
        $this->assertTrue($body->uncompress('zlib.inflate'));
        $this->assertEquals('test', (string)$body);
        unset($body);

        // Test using a very long string
        $largeString = '';
        for ($i = 0; $i < 25000; $i++) {
            $largeString .= chr(rand(33, 126));
        }
        $body = EntityBody::factory($largeString);
        $this->assertEquals($largeString, (string)$body);
        $this->assertTrue($body->compress());
        $this->assertNotEquals($largeString, (string)$body);
        $compressed = (string)$body;
        $this->assertTrue($body->uncompress());
        $this->assertEquals($largeString, (string)$body);
        $this->assertEquals($compressed, gzdeflate($largeString));

        $body = EntityBody::factory(fopen(__DIR__ . '/../TestData/compress_test', 'w'));
        $this->assertFalse($body->compress());
        unset($body);

        unlink(__DIR__ . '/../TestData/compress_test');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::getContentType
     */
    public function testDeterminesContentType()
    {
        // Test using a string/temp stream
        $body = EntityBody::factory('testing 123...testing 123');
        $this->assertEquals('application/octet-stream', $body->getContentType());

        // Use a local file
        $body = EntityBody::factory(fopen(__FILE__, 'r'));
        $this->assertContains('text/x-', $body->getContentType());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::getContentMd5
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::calculateMd5
     */
    public function testCreatesMd5Checksum()
    {
        $body = EntityBody::factory('testing 123...testing 123');
        $this->assertEquals(md5('testing 123...testing 123'), $body->getContentMd5());

        $server = $this->getServer()->enqueue(
            "HTTP/1.1 200 OK" . "\r\n" .
            "Content-Length: 3" . "\r\n\r\n" .
            "abc"
        );

        $body = EntityBody::factory(fopen($this->getServer()->getUrl(), 'r'));
        $this->assertFalse($body->getContentMd5());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::getContentMd5
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::calculateMd5
     */
    public function testSeeksToOriginalPosAfterMd5()
    {
        $body = EntityBody::factory('testing 123');
        $body->seek(4);
        $this->assertEquals(md5('testing 123'), $body->getContentMd5());
        $this->assertEquals(4, $body->ftell());
        $this->assertEquals('ing 123', $body->read(1000));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory
     */
    public function testGetTypeFormBodyFactoring()
    {
        $body = EntityBody::factory(array('key1' => 'val1', 'key2' => 'val2'));
        $this->assertEquals('key1=val1&key2=val2', (string)$body);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::setRewindFunction
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::rewind
     */
    public function testAllowsCustomRewind()
    {
        $body = EntityBody::factory('foo');
        $rewound = false;
        $body->setRewindFunction(function ($body) use (&$rewound) {
            $rewound = true;
            return $body->seek(0);
        });
        $body->seek(2);
        $this->assertTrue($body->rewind());
        $this->assertTrue($rewound);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::setRewindFunction
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testCustomRewindFunctionMustBeCallable()
    {
        $body = EntityBody::factory();
        $body->setRewindFunction('foo');
    }
}
