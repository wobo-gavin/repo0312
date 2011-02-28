<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ListDomainsTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\ListDomains
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractSimpleDbCommand
     */
    public function testListDomains()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\ListDomains();
        $this->assertSame($command, $command->setMaxDomains(100));
        $this->assertSame($command, $command->setIterate(true));
        $this->assertSame($command, $command->setNextToken(null));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array('ListDomainsWithNextTokenResponse', 'ListDomainsResponse'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals(array(
            'domain_1',
            'domain_2',
            'domain_3',
            'domain_4'
        ), $command->getResult());

        $this->assertContains(
            'http://sdb.amazonaws.com/?Action=ListDomains&MaxNumberOfDomains=100&Timestamp=',
            $command->getRequest()->getUrl()
        );
    }
}