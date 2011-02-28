<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportRequestListByNextToken
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class GetReportRequestListByNextTokenTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetReportRequestListByNextToken()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportRequestListByNextTokenResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report_request_list_by_next_token')
            ->setNextToken('asdf');

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Command\GetReportRequestListByNextToken', $command);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertInstanceOf('\SimpleXMLElement', $response);

        $qs = $command->getRequest()->getQuery();
        $this->assertEquals('GetReportRequestListByNextToken', $qs->get('Action'));
        $this->assertEquals('asdf', $qs->get('NextToken'));
    }
}