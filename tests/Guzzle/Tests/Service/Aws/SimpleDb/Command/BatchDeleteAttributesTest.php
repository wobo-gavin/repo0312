<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class BatchDeleteAttributesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\BatchDeleteAttributes
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractSimpleDbCommandRequiresDomain
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractBatchedCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\AbstractSimpleDbCommand
     */
    public function testBatchDeleteAttributes()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\BatchDeleteAttributes();
        $this->assertSame($command, $command->setDomain('test'));

        $command->addItem('JumboFez', array(
            'color' => array('red', 'brick', 'garnet')
        ));

        $command->addItem('PetiteFez', array(
            'color' => array('pink', 'fuscia')
        ));

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'BatchDeleteAttributesResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains('/?Action=BatchDeleteAttributes&DomainName=test&Item.1.ItemName=JumboFez&Item.1.Attribute.0.Name=color&Item.1.Attribute.0.Value=red&Item.1.Attribute.1.Name=color&Item.1.Attribute.1.Value=brick&Item.1.Attribute.2.Name=color&Item.1.Attribute.2.Value=garnet&Item.2.ItemName=PetiteFez&Item.2.Attribute.0.Name=color&Item.2.Attribute.0.Value=pink&Item.2.Attribute.1.Name=color&Item.2.Attribute.1.Value=fuscia', $command->getRequest()->getResourceUri());
    }
}