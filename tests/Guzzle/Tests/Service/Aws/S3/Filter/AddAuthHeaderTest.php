<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Filter;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AddAuthHeaderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Filter\AddAuthHeader
     */
    public function testFilter()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        $builder = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Builder(array(
            'base_url' => $this->getServer()->getUrl(),
            'access_key_id' => 'a',
            'secret_access_key' => 's'
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Filter\\AddAuthHeader'));
        $request->send();

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertContains('AWS a:', $request->getHeader('Authorization'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Filter\AddAuthHeader
     */
    public function testFilterSkipsIfNoSignatureObjectIsProvided()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');

        $filter = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Filter\AddAuthHeader(array());
        $this->assertFalse($filter->process($request));
    }
}