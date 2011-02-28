<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Mws;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\MwsBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws\MwsBuilder
 *
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class MwsBuilderTest extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testBuild()
    {
        $builder = new MwsBuilder(array(
            'merchant_id'           => 'ASDF',
            'marketplace_id'        => 'ASDF',
            'access_key_id'         => 'ASDF',
            'secret_access_key'     => 'ASDF',
            'application_name'      => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Test',
            'application_version'   => '0.1'
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
    }
}