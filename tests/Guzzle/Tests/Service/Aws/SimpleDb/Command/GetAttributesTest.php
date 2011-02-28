<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetAttributesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\GetAttributes
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractAttributeCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractSimpleDbCommand
     */
    public function testGetAttributes()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\GetAttributes();
        $this->assertSame($command, $command->setDomain('test'));
        $this->assertSame($command, $command->setItemName('item_name'));
        $this->assertSame($command, $command->setAttributeNames(array('attr1', 'attr2')));
        $this->assertSame($command, $command->setConsistentRead(true));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetAttributesResponse');

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains(
            'http://sdb.amazonaws.com/?Action=GetAttributes&DomainName=test&ItemName=item_name&AttributeName.0=attr1&AttributeName.1=attr2&ConsistentRead=true&Timestamp=',
            $command->getRequest()->getUrl()
        );

        $this->assertEquals(array (
            'attr_1' => 'value_1',
            'attr_2' => array ('value_2', 'value_3', 'value_4')
        ), $command->getResult());
    }
}