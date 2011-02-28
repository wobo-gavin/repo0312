<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReport
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\command\AbstractMwsCommand
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetReportTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetReport()
    {
        // Get /* Replaced /* Replaced /* Replaced client */ */ */
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        // Get command
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report')
            ->setReportId(12345);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReport', $command);

        // Get mock response
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportResponse');
        $report = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Model\CsvReport', $report);

        // Should have 3 rows in report
        $this->assertEquals(3, $report->count());

        // Report should have valid rows
        foreach($report as $row) {
            $this->assertArrayHasKey('item-name', $row);
        }

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetReport', $qs->get('Action'));
        $this->assertEquals('12345', $qs->get('ReportId'));
    }
}