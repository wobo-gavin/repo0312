<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class TruncateDomainTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\TruncateDomain
     */
    public function testTruncateDomain()
    {
        $this->getServer()->flush();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db', true);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl($this->getServer()->getUrl());
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'DeleteDomainResponse',
            'CreateDomainResponse'
        ));

        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\TruncateDomain();
        $this->assertSame($command, $command->setDomain('test'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains(
            $this->getServer()->getUrl() . '?Action=DeleteDomain&DomainName=test&Timestamp=',
            $command->getRequest()->getUrl()
        );

        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());

        $requests = $this->getMockedRequests();
        $this->assertEquals('DeleteDomain', $requests[0]->getQuery()->get('Action'));
        $this->assertEquals('test', $requests[0]->getQuery()->get('DomainName'));
        $this->assertEquals('CreateDomain', $requests[1]->getQuery()->get('Action'));
        $this->assertEquals('test', $requests[1]->getQuery()->get('DomainName'));
    }
}