<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Signature;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SignatureV2Test extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var SignatureV2
     */
    private $signature;

    /**
     * @var array
     */
    private $_options = array(
        'method' => 'GET',
        'endpoint' => 'http://test.amazonaws.com/'
    );

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->signature = new SignatureV2('access_key', 'secret');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignReturnsEmptyString()
    {
        $params = array('A' => 'v1');
        $this->assertEmpty($this->signature->calculateStringToSign($params));
        $this->assertEmpty($this->signature->calculateStringToSign($params, array()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignAlternateSort()
    {
        $params = array(
            'A' => 'v1',
            'b' => 'v2',
            'a' => 'v3'
        );
        
        $this->assertEquals("GET\ntest.amazonaws.com\n/\nA=v1&a=v3&b=v2", $this->signature->calculateStringToSign($params, array(
            // 'method' => 'GET', // Will automatically assume GET
            'endpoint' => 'http://test.amazonaws.com/',
            'sortMethod' => 'strcmp'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignIgnoreVariable()
    {
        $params = array(
            'a' => 'v1',
            'b' => 'v2',
            'c' => 'v3'
        );
        
        $this->assertEquals("PUT\ntest.amazonaws.com\n/\na=v1&b=v2", $this->signature->calculateStringToSign($params, array(
            'method' => 'PUT',
            'endpoint' => 'https://test.amazonaws.com',
            'ignore' => 'c'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignNullParameters()
    {
        $params = array(
            'a' => '',
            'b' => 'v2',
            'c' => 'v3'
        );
        
        $this->assertEquals("GET\ntest.amazonaws.com\n/\nb=v2&c=v3", $this->signature->calculateStringToSign($params, $this->_options));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignEncode()
    {
        $params = array(
            'a' => 'test space',
            'b' => 'question?',
            'c' => '  v3'
        );
        
        $this->assertEquals("GET\ntest.amazonaws.com\n/\na=test%20space&b=question%3F&c=%20%20v3", $this->signature->calculateStringToSign($params, $this->_options));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2::calculateStringToSign
     */
    public function testCalculateStringToSignEmptyRequest()
    {
        $this->assertEquals("GET\ntest.amazonaws.com\n/\n", $this->signature->calculateStringToSign(array(), $this->_options));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2
     */
    public function testHashingAlgorithms()
    {
        $this->assertEquals('HmacSHA256', $this->signature->getAwsHashingAlgorithm());
        $this->assertEquals('sha256', $this->signature->getPhpHashingAlgorithm());
    }
}