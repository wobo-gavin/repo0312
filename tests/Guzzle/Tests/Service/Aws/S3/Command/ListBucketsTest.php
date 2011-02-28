<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ListBucketsTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\ListBuckets
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketList
     */
    public function testListBuckets()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\ListBuckets();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'ListBucketsResponse');

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://s3.amazonaws.com/', $command->getRequest()->getUrl());
        
        $list = $command->getResult();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Model\\BucketList', $list);

        $iterator = $list->getIterator();
        $this->assertInstanceOf('ArrayIterator', $iterator);
        $this->assertEquals(array('quotes;', 'samples'), $list->getBucketNames());

        $this->assertEquals(array(
            'quotes;' => array(
                'name' => 'quotes;',
                'creation_date' => '2006-02-03T16:45:09.000Z',
            ),
            'samples' => array(
                'name' => 'samples',
                'creation_date' => '2006-02-03T16:41:58.000Z'
            )
        ), $list->getBuckets());

        $this->assertEquals('bcaf1ffd86f461ca5fb16fd081034f', $list->getOwnerId());
        $this->assertEquals('webfile', $list->getOwnerDisplayName());

        $this->assertInstanceOf('SimpleXMLElement', $list->getXml());
    }
}