<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Signature;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SignatureV1Test extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var SignatureV1
     */
    private $signature;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->signature = new SignatureV1('access_key', 'secret');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::calculateStringToSign
     */
    public function testCalculateStringToSignAlternateSort()
    {
        $params = array(
            'A' => 'v1',
            'b' => 'v2',
            'a' => 'v3'
        );
        $this->assertEquals('Av1av3bv2', $this->signature->calculateStringToSign($params, array(
            'sortMethod' => 'strcmp'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::calculateStringToSign
     */
    public function testCalculateStringToSignIgnoreVariable()
    {
        $params = array(
            'a' => 'v1',
            'b' => 'v2',
            'c' => 'v3'
        );
        $this->assertEquals('av1bv2', $this->signature->calculateStringToSign($params, array(
            'ignore' => 'c'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::calculateStringToSign
     */
    public function testCalculateStringToSignNullParameters()
    {
        $params = array(
            'a' => '',
            'b' => 'v2',
            'c' => 'v3'
        );
        $this->assertEquals('bv2cv3', $this->signature->calculateStringToSign($params));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::calculateStringToSign
     */
    public function testCalculateStringToSignEmptyRequest()
    {
        $this->assertEquals('', $this->signature->calculateStringToSign(array()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::getAwsHashingAlgorithm
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1::getPhpHashingAlgorithm
     */
    public function testHashingAlgorithms()
    {
        $this->assertEquals('HmacSHA1', $this->signature->getAwsHashingAlgorithm());
        $this->assertEquals('sha1', $this->signature->getPhpHashingAlgorithm());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::getAccessKeyId
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::getSecretAccessKey
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::getVersion
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::signString
     */
    public function testAbstractSignature()
    {
        $signature = new SignatureV1('a', 's');

        $this->assertEquals('a', $signature->getAccessKeyId());
        $this->assertEquals('s', $signature->getSecretAccessKey());
        $this->assertEquals('1', $signature->getVersion());

        // Test signing a string
        $this->assertEquals('t+ODukdfc3usAF+HextTblYraxs=', $signature->signString('abc'));
        $this->assertEquals('6t9wEpYa6HJk9JwrM7mZbmsLhF4=', $signature->signString('abc' . chr(240)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::__construct
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AwsException
     */
    public function testAbstractSignatureRequiresAccessKeyId()
    {
        $signature = new SignatureV1(null, 's');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature::__construct
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AwsException
     */
    public function testAbstractSignatureRequiresSecretAccessKey()
    {
        $signature = new SignatureV1('a', null);
    }
}