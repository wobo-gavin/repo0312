<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\SimpleDb;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SimpleDbBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbBuilder::build
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbBuilder::getClass
     */
    public function testBuild()
    {
        $builder = $this->getServiceBuilder()->getBuilder('test.simple_db');

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbBuilder', $builder);
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\SimpleDb\\SimpleDbClient', $builder->getClass());
        $this->assertEquals('test.simple_db', $builder->getName());

        // Make sure the builder creates a valid /* Replaced /* Replaced /* Replaced client */ */ */ objects
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\SimpleDb\\SimpleDbClient', $/* Replaced /* Replaced /* Replaced client */ */ */);

        // Make sure the query string auth signing plugin was attached
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->hasPlugin('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin'));
    }
}