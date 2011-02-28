<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportScheduleListByNextToken
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetReportScheduleListByNextTokenTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetReportScheduleListByNextToken()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportScheduleListByNextTokenResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report_schedule_list_by_next_token')
            ->setNextToken('asdf');

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportScheduleListByNextToken', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetReportScheduleListByNextToken', $qs->get('Action'));
        $this->assertEquals('asdf', $qs->get('NextToken'));
    }
}