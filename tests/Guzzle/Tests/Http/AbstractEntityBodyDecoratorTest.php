<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\AbstractEntityBodyDecorator
 */
class AbstractEntityBodyDecoratorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testDecoratesEntityBody()
    {
        $e = EntityBody::factory();
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\AbstractEntityBodyDecorator', array($e));

        $this->assertSame($e->getStream(), $mock->getStream());
        $this->assertSame($e->getContentLength(), $mock->getContentLength());
        $this->assertSame($e->getSize(), $mock->getSize());
        $this->assertSame($e->getContentMd5(), $mock->getContentMd5());
        $this->assertSame($e->getContentType(), $mock->getContentType());
        $this->assertSame($e->__toString(), $mock->__toString());
        $this->assertSame($e->getUri(), $mock->getUri());
        $this->assertSame($e->getStreamType(), $mock->getStreamType());
        $this->assertSame($e->getWrapper(), $mock->getWrapper());
        $this->assertSame($e->getWrapperData(), $mock->getWrapperData());
        $this->assertSame($e->isReadable(), $mock->isReadable());
        $this->assertSame($e->isWritable(), $mock->isWritable());
        $this->assertSame($e->isConsumed(), $mock->isConsumed());
        $this->assertSame($e->isLocal(), $mock->isLocal());
        $this->assertSame($e->isSeekable(), $mock->isSeekable());
        $this->assertSame($e->getContentEncoding(), $mock->getContentEncoding());
    }
}
