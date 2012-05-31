<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\PostFile;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\PostFile
 */
class PostFileTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testConstructorConfiguresPostFile()
    {
        $file = new PostFile('foo', __FILE__, 'x-foo');
        $this->assertEquals('foo', $file->getFieldName());
        $this->assertEquals(__FILE__, $file->getFilename());
        $this->assertEquals('x-foo', $file->getContentType());
    }

    public function testRemovesLeadingAtSymbolFromPath()
    {
        $file = new PostFile('foo', '@' . __FILE__);
        $this->assertEquals(__FILE__, $file->getFilename());
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresFileIsReadable()
    {
        $file = new PostFile('foo', '/foo/baz/bar');
    }

    public function testCanChangeContentType()
    {
        $file = new PostFile('foo', '@' . __FILE__);
        $file->setContentType('Boo');
        $this->assertEquals('Boo', $file->getContentType());
    }

    public function testCanChangeFieldName()
    {
        $file = new PostFile('foo', '@' . __FILE__);
        $file->setFieldName('Boo');
        $this->assertEquals('Boo', $file->getFieldName());
    }

    public function testReturnsCurlValueString()
    {
        $file = new PostFile('foo', __FILE__);
        $this->assertEquals('@' . __FILE__ . ';type=text/x-php', $file->getCurlString());
    }
}
