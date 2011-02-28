<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportList
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\AbstractMwsCommand
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetReportListTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetReportList()
    {
        // Get /* Replaced /* Replaced /* Replaced client */ */ */
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportListResponse');

        // Test command
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report_list')
            ->setMaxCount(10)
            ->setReportTypeList(array(
                Type\ReportType::MERCHANT_LISTINGS_REPORT
            ))
            ->setReportRequestIdList(array(
                12345
            ))
            ->setAcknowledged(true)
            ->setAvailableFromDate(new \DateTime('2011-01-01'))
            ->setAvailableToDate(new \DateTime());
        
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportList', $command);

        // Response should be a SimpleXMLElement
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetReportList', $qs->get('Action'));
        $this->assertEquals('10', $qs->get('MaxCount'));
        $this->assertEquals('_GET_MERCHANT_LISTINGS_DATA_', $qs->get('ReportTypeList.Type.1'));
        $this->assertEquals('12345', $qs->get('ReportRequestIdList.Id.1'));
        $this->assertEquals('true', $qs->get('Acknowledged'));
        $this->assertArrayHasKey('AvailableFromDate', $qs->getAll());
        $this->assertArrayHasKey('AvailableToDate', $qs->getAll());
    }
}