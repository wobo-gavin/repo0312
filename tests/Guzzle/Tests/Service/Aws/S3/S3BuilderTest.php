<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class S3BuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Builder::build
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Builder::setDevPayTokens
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Builder::getClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractBuilder
     */
    public function testBuild()
    {
        $builder = $this->getServiceBuilder()->getBuilder('test.s3');

        // Set some DevPay tokens to have the builder add the DevPay filter
        $this->assertSame($builder, $builder->setDevPayTokens('123', 'abc'));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Builder', $builder);
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\S3Client', $builder->getClass());
        $this->assertEquals('test.s3', $builder->getName());

        // Make sure the builder creates a valid /* Replaced /* Replaced /* Replaced client */ */ */ objects
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\S3Client', $/* Replaced /* Replaced /* Replaced client */ */ */);

        // Make sure the signing plugin was attached
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->hasPlugin('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\SignS3RequestPlugin'));

        // Make sure the builder added the Authentication filter for preparing requests
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Filter\\AddAuthHeader'));

        // Make sure the builder adds the DevPay token filter when preparing requests
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Filter\\DevPayTokenHeaders'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractBuilder
     */
    public function testAbstractBuilder()
    {
        $builder = $this->getServiceBuilder()->getBuilder('test.s3');

        $this->assertSame($builder, $builder->setAuthentication('123', 'abc'));
        $this->assertSame($builder, $builder->setVersion('1'));
        $this->assertSame($builder, $builder->setSignature(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Signature('123', 'abc')));
    }
}