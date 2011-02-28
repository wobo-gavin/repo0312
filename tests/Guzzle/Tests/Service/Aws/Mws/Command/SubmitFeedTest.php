<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\SubmitFeed
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class SubmitFeedTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testSubmitFeed()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'SubmitFeedResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('submit_feed')
            ->setFeedContent('asdf')
            ->setFeedType(Type\FeedType::PRODUCT_FEED)
            ->setPurgeAndReplace(true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\SubmitFeed', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('SubmitFeed', $qs->get('Action'));
        $this->assertEquals('_POST_PRODUCT_DATA_', $qs->get('FeedType'));
        $this->assertEquals('true', $qs->get('PurgeAndReplace'));
    }
}