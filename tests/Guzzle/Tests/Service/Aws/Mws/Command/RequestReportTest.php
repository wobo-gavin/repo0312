<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\RequestReport
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class RequestReportTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRequestReport()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'RequestReportResponse');
        
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('request_report')
            ->setReportType(Type\ReportType::MERCHANT_LISTINGS_REPORT)
            ->setStartDate(new \DateTime('2011-01-01'))
            ->setEndDate(new \DateTime());

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\RequestReport', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('RequestReport', $qs->get('Action'));
        $this->assertEquals('_GET_MERCHANT_LISTINGS_DATA_', $qs->get('ReportType'));
        $this->assertArrayHasKey('StartDate', $qs->getAll());
        $this->assertArrayHasKey('EndDate', $qs->getAll());
    }
}