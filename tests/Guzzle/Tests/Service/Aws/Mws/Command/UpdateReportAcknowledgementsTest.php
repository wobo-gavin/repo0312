<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Type;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\UpdateReportAcknowledgements
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class UpdateReportAcknowledgementsTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testUpdateReportAcknowledgements()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'UpdateReportAcknowledgementsResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('update_report_acknowledgements')
            ->setReportIdList(array(
                12345
            ))
            ->setAcknowledged(true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\UpdateReportAcknowledgements', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('UpdateReportAcknowledgements', $qs->get('Action'));
        $this->assertEquals('12345', $qs->get('ReportIdList.Id.1'));
        $this->assertEquals('true', $qs->get('Acknowledged'));
    }
}