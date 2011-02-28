<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MultipartFormDataTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::__construct
     */
    public function testConstructorSetsInitialFields()
    {
        $e = new MultipartFormData(array(
            'a' => 'b',
            'b' => 'c'
        ), array(
            'file[0]' => __FILE__
        ));

        $this->validateTest($e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::setPostData
     */
    public function testPostDataCanBeChangedAtAnyTime()
    {
        $e = new MultipartFormData();
        $e->setPostData(array(
            'a' => 'b',
            'b' => 'c'
        ), array(
            'file[0]' => __FILE__
        ));

        $this->validateTest($e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::__toString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::getEntityBody
     */
    public function testManagesEntityBody()
    {
        $e = new MultipartFormData(array(
            'a' => 'b',
            'b' => 'c'
        ), array(
            'file[0]' => __FILE__
        ));

        $this->assertInternalType('string', (string)$e);
        $this->assertEquals((string) $e->getEntityBody(), (string)$e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::fromRequestfactory
     */
    public function testCanCreatesEntityBodyUsingRequest()
    {
        $r = new EntityEnclosingRequest('POST', 'http://www.test.com/');
        $r->addPostFields(array(
            'a' => 'b',
            'b' => 'c'
        ))->addPostFiles(array(
            'file[0]' => __FILE__
        ));

        $e = MultipartFormData::fromRequestfactory($r);

        $this->validateTest($e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\MultipartFormData::setPostData
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpException
     * @expectedExceptionMessage Unable to open file blee_bloo_blop.not_there
     */
    public function testValidatesFilesAreReadable()
    {
        $e = new MultipartFormData(array(), array(
            'test_file' => 'blee_bloo_blop.not_there'
        ));
    }

    /**
     * Create a multipart upload object and validate it
     */
    protected function validateTest($m)
    {
        $eb = (string) $m->getEntityBody();

        $this->assertContains("Content-Disposition: form-data; name=\"a\"\r\n\r\nb\r\n", $eb);
        $this->assertContains("Content-Disposition: form-data; name=\"b\"\r\n\r\nc\r\n", $eb);
        $this->assertContains("Content-Disposition: form-data; name=\"file[0]\"; filename=\"MultipartFormDataTest.php\"\r\nContent-Type: text/x-php\r\n\r\n", $eb);
        $this->assertContains(file_get_contents(__FILE__), $eb);
    }
}