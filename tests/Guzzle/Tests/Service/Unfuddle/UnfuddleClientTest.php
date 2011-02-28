<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Unfuddle;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\UnfuddleClient;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;

/**
 * @group Unfuddle
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class UnfuddleClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\UnfuddleClient::__construct
     */
    public function test__construct()
    {
        $b = new ConcreteDescriptionBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Unfuddle\\UnfuddleClient');
        $s = $b->build();
        $f = new ConcreteCommandFactory($s);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new UnfuddleClient(array(
            'username' => 'abe',
            'password' => 'lincoln',
            'subdomain' => 'president'
        ), $s, $f);

        $this->assertEquals('https://president.unfuddle.com/api/v1/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\UnfuddleClient::getRequest
     */
    public function testConfiguresUnfuddleRequests()
    {
        $b = new ConcreteDescriptionBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Unfuddle\\UnfuddleClient');
        $s = $b->build();
        $f = new ConcreteCommandFactory($s);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new UnfuddleClient(array(
            'username' => 'abe',
            'password' => 'lincoln',
            'subdomain' => 'president'
        ), $s, $f);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertEquals('abe', $request->getUsername());
        $this->assertEquals('lincoln', $request->getPassword());
        $this->assertEquals('', $request->getQuery()->getPrefix());
        $this->assertEquals('/', $request->getQuery()->getFieldSeparator());
        $this->assertEquals('/', $request->getQuery()->getValueSeparator());
    }
}