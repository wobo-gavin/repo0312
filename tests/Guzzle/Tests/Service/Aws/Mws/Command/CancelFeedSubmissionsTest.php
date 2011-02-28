<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\CancelFeedSubmissions
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class CancelFeedSubmissionsTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCancelFeedSubmissions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'CancelFeedSubmissionsResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('cancel_feed_submissions')
            ->setFeedSubmissionIdList(array(
                12345
            ))
            ->setFeedTypeList(array(
                Type\FeedType::PRODUCT_FEED
            ))
            ->setSubmittedFromDate(new \DateTime('2011-01-01'))
            ->setSubmittedToDate(new \DateTime('2011-01-10'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\CancelFeedSubmissions', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('CancelFeedSubmissions', $qs->get('Action'));
        $this->assertEquals('12345', $qs->get('FeedSubmissionIdList.Id.1'));
        $this->assertEquals('_POST_PRODUCT_DATA_', $qs->get('FeedTypeList.Type.1'));
        $this->assertArrayHasKey('SubmittedFromDate', $qs->getAll());
        $this->assertArrayHasKey('SubmittedToDate', $qs->getAll());
    }
}