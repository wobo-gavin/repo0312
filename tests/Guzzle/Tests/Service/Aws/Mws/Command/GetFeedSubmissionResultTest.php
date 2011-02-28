<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionResult
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetFeedSubmissionResult extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetFeedSubmissionResult()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetFeedSubmissionResultResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_feed_submission_result')
            ->setFeedSubmissionId(12345);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetFeedSubmissionResult', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetFeedSubmissionResult', $qs->get('Action'));
        $this->assertEquals('12345', $qs->get('FeedSubmissionId'));
    }
}