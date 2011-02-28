<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportScheduleList
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetReportScheduleListTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetReportScheduleList()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportScheduleListResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report_schedule_list')
            ->setReportTypeList(array(
                Type\ReportType::MERCHANT_LISTINGS_REPORT
            ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportScheduleList', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);
        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetReportScheduleList', $qs->get('Action'));
        $this->assertEquals('_GET_MERCHANT_LISTINGS_DATA_', $qs->get('ReportTypeList.Type.1'));
    }
}