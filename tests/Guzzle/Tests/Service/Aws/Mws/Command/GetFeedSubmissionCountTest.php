<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionCount
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetFeedSubmissionCountTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetFeedSubmissionCount()
    {
        // Get /* Replaced /* Replaced /* Replaced client */ */ */
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetFeedSubmissionCountResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_feed_submission_count')
            ->setFeedTypeList(array(
                Type\FeedType::PRODUCT_FEED
            ))
            ->setFeedProcessingStatusList(array(
                Type\FeedProcessingStatus::DONE
            ))
            ->setSubmittedFromDate(new \DateTime('2011-01-01'))
            ->setSubmittedToDate(new \DateTime());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionCount', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetFeedSubmissionCount', $qs->get('Action'));
        $this->assertEquals('_POST_PRODUCT_DATA_', $qs->get('FeedTypeList.Type.1'));
        $this->assertEquals('_DONE_', $qs->get('FeedProcessingStatusList.Status.1'));
        $this->assertArrayHasKey('SubmittedFromDate', $qs->getAll());
        $this->assertArrayHasKey('SubmittedToDate', $qs->getAll());
    }
}
