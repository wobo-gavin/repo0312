<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DeleteAttributesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\DeleteAttributes
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractAttributeCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractSimpleDbCommand
     */
    public function testDeleteAttributes()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\DeleteAttributes();
        $this->assertSame($command, $command->setDomain('test'));
        $this->assertSame($command, $command->setItemName('item_name'));
        $this->assertSame($command, $command->setAttributeNames(array('attr1', 'attr2')));
        $this->assertSame($command, $command->addExpected('test_attr', 'abc', true));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'DeleteAttributesResponse');

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains(
            'http://sdb.amazonaws.com/?Action=DeleteAttributes&DomainName=test&ItemName=item_name&AttributeName.0=attr1&AttributeName.1=attr2&Expected.0.Name=test_attr&Expected.0.Value=abc&Expected.0.Exists=true&Timestamp=',
            $command->getRequest()->getUrl()
        );
    }
}