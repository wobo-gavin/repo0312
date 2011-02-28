<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Model\Feed;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Model\Feed
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class FeedTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testFeed()
    {
        $feed = new Feed();

        $feed->setMessage('<Sample />');

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement', $feed->getMessage());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement', $feed->getXml());
        $this->assertContains('<?xml', $feed->toXml());
        $this->assertContains('<?xml', (string)$feed);
    }
}