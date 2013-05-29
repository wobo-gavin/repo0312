<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message\Header;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\Link;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

class LinkTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testParsesLinks()
    {
        $link = new Link('Link', '<http:/.../front.jpeg>; rel=front; type="image/jpeg", <http://.../back.jpeg>; rel=back; type="image/jpeg"');
        $links = $link->getLinks();
        $this->assertEquals(array(
            array(
                'rel' => 'front',
                'type' => 'image/jpeg',
                'url' => 'http:/.../front.jpeg',
            ),
            array(
                'rel' => 'back',
                'type' => 'image/jpeg',
                'url' => 'http://.../back.jpeg',
            ),
        ), $links);

        $this->assertEquals(array(
            'rel' => 'back',
            'type' => 'image/jpeg',
            'url' => 'http://.../back.jpeg',
        ), $link->getLink('back'));

        $this->assertTrue($link->hasLink('front'));
        $this->assertFalse($link->hasLink('foo'));
    }
}
