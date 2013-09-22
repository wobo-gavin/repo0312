<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;

/**
 * @group server
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream
 */
class StreamTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorThrowsExceptionOnInvalidArgument()
    {
        new Stream(true);
    }

    public function testConstructorInitializesProperties()
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, 'data');
        $stream = new Stream($handle);
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('php://temp', $stream->getMetadata('uri'));
        $this->assertInternalType('array', $stream->getMetadata());
        $this->assertEquals(4, $stream->getSize());
        $this->assertFalse($stream->eof());
        $stream->close();
    }

    public function testStreamClosesHandleOnDestruct()
    {
        $handle = fopen('php://temp', 'r');
        $stream = new Stream($handle);
        unset($stream);
        $this->assertFalse(is_resource($handle));
    }

    public function testConvertsToString()
    {
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, 'data');
        $stream = new Stream($handle);
        $this->assertEquals('data', (string) $stream);
        $this->assertEquals('data', (string) $stream);
        $stream->close();
    }

    public function testGetsContents()
    {
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, 'data');
        $stream = new Stream($handle);
        $this->assertEquals('', $stream->getContents());
        $stream->seek(0);
        $this->assertEquals('data', $stream->getContents());
        $this->assertEquals('', $stream->getContents());
        $stream->seek(0);
        $this->assertEquals('da', $stream->getContents(2));
        $stream->close();
    }

    public function testChecksEof()
    {
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, 'data');
        $stream = new Stream($handle);
        $this->assertFalse($stream->eof());
        $stream->read(4);
        $this->assertTrue($stream->eof());
        $stream->close();
    }

    public function testAllowsSettingManualSize()
    {
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, 'data');
        $stream = new Stream($handle);
        $stream->setSize(10);
        $this->assertEquals(10, $stream->getSize());
        $stream->close();
    }

    public function testGetSize()
    {
        $size = filesize(__FILE__);
        $handle = fopen(__FILE__, 'r');
        $stream = new Stream($handle);
        $this->assertEquals($size, $stream->getSize());
        // Load from cache
        $this->assertEquals($size, $stream->getSize());
        $stream->close();
    }

    public function testEnsuresSizeIsConsistent()
    {
        $h = fopen('php://temp', 'w+');
        $this->assertEquals(3, fwrite($h, 'foo'));
        $stream = new Stream($h);
        $this->assertEquals(3, $stream->getSize());
        $this->assertEquals(4, $stream->write('test'));
        $this->assertEquals(7, $stream->getSize());
        $this->assertEquals(7, $stream->getSize());
        $stream->close();
    }

    public function testProvidesStreamPosition()
    {
        $handle = fopen('php://temp', 'w+');
        $stream = new Stream($handle);
        $this->assertEquals(0, $stream->tell());
        $stream->write('foo');
        $this->assertEquals(3, $stream->tell());
        $stream->seek(1);
        $this->assertEquals(1, $stream->tell());
        $this->assertSame(ftell($handle), $stream->tell());
        $stream->close();
    }

    public function testCanDetachStream()
    {
        $r = fopen('php://temp', 'w+');
        $stream = new Stream($r);
        $this->assertTrue($stream->isReadable());
        $stream->detach();
        $this->assertFalse($stream->isReadable());
        $stream->close();
    }

    public function testCreatesFromString()
    {
        $stream = Stream::fromString('foo');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream', $stream);
        $this->assertEquals('', $stream->getContents());
        $this->assertEquals('foo', (string) $stream);
        $stream->close();
    }

    public function testFactoryCreatesFromStringAndIgnoresSameType()
    {
        $s = Stream::factory();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream', $s);
        $this->assertSame($s, Stream::factory($s));
    }

    public function testFactoryCreatesFromResource()
    {
        $r = fopen(__FILE__, 'r');
        $s = Stream::factory($r);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream', $s);
        $this->assertSame(file_get_contents(__FILE__), (string) $s);
    }

    public function testFactoryCreatesFromObjectWithToString()
    {
        $r = new HasToString();
        $s = Stream::factory($r);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream', $s);
        $this->assertEquals('foo', (string) $s);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForUnknown()
    {
        Stream::factory(new \stdClass());
    }
}

class HasToString
{
    public function __toString() {
        return 'foo';
    }
}
