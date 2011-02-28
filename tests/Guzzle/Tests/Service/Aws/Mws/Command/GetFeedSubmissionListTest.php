<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionList
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetFeedSubmissionListTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetFeedSubmissionList()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetFeedSubmissionListResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_feed_submission_list')
            ->setFeedSubmissionIdList(array(
                123
            ))
            ->setMaxCount(10)
            ->setFeedTypeList(array(
                Type\FeedType::PRODUCT_FEED
            ))
            ->setFeedProcessingStatusList(array(
                Type\FeedProcessingStatus::DONE
            ))
            ->setSubmittedFromDate(new \DateTime('2011-01-01'))
            ->setSubmittedToDate(new \DateTime());

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionList', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetFeedSubmissionList', $qs->get('Action'));
        $this->assertEquals('123', $qs->get('FeedSubmissionIdList.Id.1'));
        $this->assertEquals('10', $qs->get('MaxCount'));
        $this->assertEquals('_POST_PRODUCT_DATA_', $qs->get('FeedTypeList.Type.1'));
        $this->assertEquals('_DONE_', $qs->get('FeedProcessingStatusList.Status.1'));
        $this->assertArrayHasKey('SubmittedFromDate', $qs->getAll());
        $this->assertArrayHasKey('SubmittedToDate', $qs->getAll());
    }
}
