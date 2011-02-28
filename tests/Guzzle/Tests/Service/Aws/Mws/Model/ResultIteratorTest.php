<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws\Model;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\Model\ResultIterator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

class ResultIteratorTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function test__construct()
    {
        // Try to iterate over a non-iterable command, should throw an exception
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report');
        $this->setExpectedException('InvalidArgumentException');
        $iterator = new ResultIterator($/* Replaced /* Replaced /* Replaced client */ */ */, $command);
    }

    public function testResultIterator()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.mws');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportListResponse');

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('get_report_list');
        $iterator = new ResultIterator($/* Replaced /* Replaced /* Replaced client */ */ */, $command);

        foreach($iterator as $key => $row) {
            $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetReportListByNextTokenResponse');
            $this->assertInstanceOf('\SimpleXMLElement', $row);
            $this->assertStringMatchesFormat('%d_%d', $iterator->key());
        }
        
    }
}