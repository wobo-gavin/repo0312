<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SqsBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\SqsBuilder::build
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\SqsBuilder::getClass
     */
    public function testBuild()
    {
        $builder = $this->getServiceBuilder()->getBuilder('test.sqs');

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\SqsBuilder', $builder);
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\Sqs\\SqsClient', $builder->getClass());
        $this->assertEquals('test.sqs', $builder->getName());

        // Make sure the builder creates a valid /* Replaced /* Replaced /* Replaced client */ */ */ objects
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\Sqs\\SqsClient', $/* Replaced /* Replaced /* Replaced client */ */ */);

        // Make sure the query string auth signing plugin was attached
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->hasPlugin('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin'));
    }
}