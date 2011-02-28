<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\ManageReportSchedule
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class ManageReportScheduleTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testManageReportSchedule()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'ManageReportScheduleResponse');
        
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('manage_report_schedule')
            ->setReportType(Type\ReportType::MERCHANT_LISTINGS_REPORT)
            ->setSchedule(Type\Schedule::EVERY_HOUR)
            ->setScheduledDate(new \DateTime());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\ManageReportSchedule', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);
        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('ManageReportSchedule', $qs->get('Action'));
        $this->assertEquals('_GET_MERCHANT_LISTINGS_DATA_', $qs->get('ReportType'));
        $this->assertEquals('_1_HOUR_', $qs->get('Schedule'));
        $this->assertArrayHasKey('ScheduledDate', $qs->getAll());
    }
}
