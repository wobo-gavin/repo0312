<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\MockObserver;

/**
 * @group server
 */
class CurlHandleTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExpectsCurlResource()
    {
        $h = new CurlHandle(false, array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testConstructorExpectsProperOptions()
    {
        $h = curl_init($this->getServer()->getUrl());
        try {
            $ha = new CurlHandle($h, false);
            $this->fail('Expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
        }

       $ha = new CurlHandle($h, array(
           CURLOPT_URL => $this->getServer()->getUrl()
       ));
       $this->assertEquals($this->getServer()->getUrl(), $ha->getOptions()->get(CURLOPT_URL));

       $ha = new CurlHandle($h, new Collection(array(
           CURLOPT_URL => $this->getServer()->getUrl()
       )));
       $this->assertEquals($this->getServer()->getUrl(), $ha->getOptions()->get(CURLOPT_URL));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getHandle
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getOptions
     */
    public function testConstructorInitializesObject()
    {
        $handle = curl_init($this->getServer()->getUrl());
        $h = new CurlHandle($handle, array(
            CURLOPT_URL => $this->getServer()->getUrl()
        ));
        $this->assertSame($handle, $h->getHandle());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Url', $h->getUrl());
        $this->assertEquals($this->getServer()->getUrl(), (string) $h->getUrl());
        $this->assertEquals($this->getServer()->getUrl(), $h->getOptions()->get(CURLOPT_URL));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getStderr
     */
    public function testStoresStdErr()
    {
        $h = CurlHandle::factory(RequestFactory::create('GET', 'http://test.com'));
        $this->assertEquals($h->getStderr(true), $h->getOptions()->get(CURLOPT_STDERR));
        $this->assertInternalType('resource', $h->getStderr(true));
        $this->assertInternalType('string', $h->getStderr(false));
        $r = $h->getStderr(true);
        fwrite($r, 'test');
        $this->assertEquals('test', $h->getStderr(false));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::setErrorNo
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getErrorNo
     */
    public function testStoresCurlErrorNumber()
    {
        $h = new CurlHandle(curl_init('http://test.com'), array(CURLOPT_URL => 'http://test.com'));
        $this->assertEquals(CURLE_OK, $h->getErrorNo());
        $h->setErrorNo(CURLE_OPERATION_TIMEOUTED);
        $this->assertEquals(CURLE_OPERATION_TIMEOUTED, $h->getErrorNo());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getStderr
     */
    public function testAccountsForMissingStdErr()
    {
        $handle = curl_init('http://www.test.com/');
        $h = new CurlHandle($handle, array(
            CURLOPT_URL => 'http://www.test.com/'
        ));
        $this->assertNull($h->getStderr(false));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::isAvailable
     */
    public function testDeterminesIfResourceIsAvailable()
    {
        $handle = curl_init($this->getServer()->getUrl());
        $h = new CurlHandle($handle, array());
        $this->assertTrue($h->isAvailable());

        // Mess it up by closing the handle
        curl_close($handle);
        $this->assertFalse($h->isAvailable());

        // Mess it up by unsetting the handle
        $handle = null;
        $this->assertFalse($h->isAvailable());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getError
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getErrorNo
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getInfo
     */
    public function testWrapsErrorsAndInfo()
    {
        if (!defined('CURLOPT_TIMEOUT_MS')) {
            $this->markTestSkipped('Update curl');
        }

        $handle = curl_init($this->getServer()->getUrl());
        curl_setopt($handle, CURLOPT_TIMEOUT_MS, 1);
        $h = new CurlHandle($handle, array(
            CURLOPT_TIMEOUT_MS => 1
        ));
        @curl_exec($handle);

        $this->assertEquals('Timeout was reached', $h->getError());
        $this->assertEquals(CURLE_OPERATION_TIMEOUTED, $h->getErrorNo());
        $this->assertEquals($this->getServer()->getUrl(), $h->getInfo(CURLINFO_EFFECTIVE_URL));
        $this->assertInternalType('array', $h->getInfo());

        curl_close($handle);
        $this->assertEquals(null, $h->getInfo('url'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::getOptions
     */
    public function testWrapsCurlOptions()
    {
        $handle = curl_init($this->getServer()->getUrl());
        $h = new CurlHandle($handle, array(
            CURLOPT_AUTOREFERER => true,
            CURLOPT_BUFFERSIZE => 1024
        ));

        $this->assertEquals(true, $h->getOptions()->get(CURLOPT_AUTOREFERER));
        $this->assertEquals(1024, $h->getOptions()->get(CURLOPT_BUFFERSIZE));
    }

    /**
     * Data provider for factory tests
     *
     * @return array
     */
    public function dataProvider()
    {
        $testFile = __DIR__ . '/../../../../../phpunit.xml';
        $postBody = new QueryString(array(
            'file' => '@' . $testFile
        ));

        $qs = new QueryString(array(
            'x' => 'y',
            'z' => 'a'
        ));

        $userAgent = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent();
        $auth = base64_encode('michael:123');
        $testFileSize = filesize($testFile);

        return array(
            // Send a regular GET
            array('GET', 'http://www.google.com/', null, null, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_HTTPHEADER => array('Host: www.google.com', 'User-Agent: ' . $userAgent),
            )),
            // Test that custom request methods can be used
            array('TRACE', 'http://www.google.com/', null, null, array(
                CURLOPT_CUSTOMREQUEST => 'TRACE'
            )),
            // Send a GET using a port
            array('GET', 'http://127.0.0.1:8080', null, null, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_PORT => 8080,
                CURLOPT_HTTPHEADER => array('Host: 127.0.0.1:8080', 'User-Agent: ' . $userAgent),
            )),
            // Send a HEAD request
            array('HEAD', 'http://www.google.com/', null, null, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_HTTPHEADER => array('Host: www.google.com', 'User-Agent: ' . $userAgent),
                CURLOPT_CUSTOMREQUEST => 'HEAD',
                CURLOPT_NOBODY => 1
            )),
            // Send a GET using basic auth
            array('GET', 'https://michael:123@localhost/index.html?q=2', null, null, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_HTTPHEADER => array(
                    'Host: localhost',
                    'Authorization: Basic ' . $auth,
                    'User-Agent: ' . $userAgent
                ),
                CURLOPT_PORT => 443
            )),
            // Send a GET request with custom headers
            array('GET', 'http://localhost:8124/', array(
                    'x-test-data' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */'
                ), null, array(
                    CURLOPT_PORT => 8124,
                    CURLOPT_HTTPHEADER => array(
                        'Host: localhost:8124',
                        'x-test-data: /* Replaced /* Replaced /* Replaced Guzzle */ */ */',
                        'User-Agent: ' . $userAgent
                )
            ), array(
                '_Accept'          => '*',
                '_Accept-Encoding' => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'x-test-data'      => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */'
            )),
            // Send a POST using a query string
            array('POST', 'http://localhost:8124/post.php', null, $qs, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_POSTFIELDS => 'x=y&z=a',
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue',
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ), array(
                '_Accept'          => '*',
                '_Accept-Encoding' => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Content-Length'   => '7',
                'Expect'           => '100-Continue',
                'Content-Type'     => 'application/x-www-form-urlencoded',
                '!Transfer-Encoding' => null
            )),
            // Send a PUT using raw data
            array('PUT', 'http://localhost:8124/put.php', null, EntityBody::factory(fopen($testFile, 'r+')), array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_READFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_INFILESIZE => filesize($testFile),
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue'
                )
            ), array(
                '_Accept'          => '*',
                '_Accept-Encoding' => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Expect'           => '100-Continue',
                'Content-Length'   => $testFileSize,
                '!Transfer-Encoding' => null
            )),
            // Send a POST request using an array of fields
            array('POST', 'http://localhost:8124/post.php', null, array(
                'x' => 'y',
                'a' => 'b'
            ), array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => 'x=y&a=b',
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue',
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ), array(
                '_Accept'          => '*',
                '_Accept-Encoding' => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Content-Length'   => '7',
                'Expect'           => '100-Continue',
                'Content-Type'     => 'application/x-www-form-urlencoded',
                '!Transfer-Encoding' => null
            )),
            // Send a POST request using a POST file
            array('POST', 'http://localhost:8124/post.php', null, $postBody, array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'file' => '@' . $testFile
                ),
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue',
                    'Content-Type: multipart/form-data'
                )
            ), array(
                '_Accept'          => '*',
                '_Accept-Encoding' => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Content-Length'   => '*',
                'Expect'           => '100-Continue',
                'Content-Type'     => 'multipart/form-data; boundary=*',
                '!Transfer-Encoding' => null
            )),
            // Send a POST request with raw POST data and a custom content-type
            array('POST', 'http://localhost:8124/post.php', array(
                'Content-Type' => 'application/json'
            ), '{"hi":"there"}', array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'Content-Type: application/json',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue',
                    'Content-Length: 14'
                ),
            ), array(
                '_Accept-Encoding' => '*',
                '_Accept'          => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Content-Type'     => 'application/json',
                'Expect'           => '100-Continue',
                'Content-Length'   => '14',
                '!Transfer-Encoding' => null
            )),
            // Send a POST request with raw POST data, a custom content-type, and use chunked encoding
            array('POST', 'http://localhost:8124/post.php', array(
                'Content-Type'      => 'application/json',
                'Transfer-Encoding' => 'chunked'
            ), '{"hi":"there"}', array(
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_WRITEFUNCTION => 'callback',
                CURLOPT_HEADERFUNCTION => 'callback',
                CURLOPT_PROGRESSFUNCTION => 'callback',
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array (
                    'Host: localhost:8124',
                    'Content-Type: application/json',
                    'User-Agent: ' . $userAgent,
                    'Expect: 100-Continue',
                    'Transfer-Encoding: chunked'
                ),
            ), array(
                '_Accept-Encoding' => '*',
                '_Accept'          => '*',
                'Host'             => '*',
                'User-Agent'       => '*',
                'Content-Type'     => 'application/json',
                'Expect'           => '100-Continue',
                'Transfer-Encoding' => 'chunked',
                '!Content-Length'  => ''
            )),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle::factory
     * @dataProvider dataProvider
     */
    public function testFactoryCreatesCurlBasedOnRequest($method, $url, $headers, $body, $options, $expectedHeaders = null)
    {
        $request = RequestFactory::create($method, $url, $headers, $body);
        $handle = CurlHandle::factory($request);

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Curl\\CurlHandle', $handle);
        $o = $request->getParams()->get('curl.last_options');

        $check = 0;
        foreach ($options as $key => $value) {
            $check++;
            $this->assertArrayHasKey($key, $o, '-> Check number ' . $check);
            if ($key != CURLOPT_HTTPHEADER && $key != CURLOPT_POSTFIELDS && (is_array($o[$key])) || $o[$key] instanceof \Closure) {
                $this->assertEquals('callback', $value, '-> Check number ' . $check);
            } else {
                $this->assertTrue($value == $o[$key], '-> Check number ' . $check . ' - ' . var_export($value, true) . ' != ' . var_export($o[$key], true));
            }
        }

        // If we are testing the actual sent headers
        if ($expectedHeaders) {

            // Send the request to the test server
            $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
            $request->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
            $this->getServer()->flush();
            $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
            $request->send();

            // Get the request that was sent and create a request that we expected
            $requests = $this->getServer()->getReceivedRequests(true);

            $this->assertFalse($this->filterHeaders($expectedHeaders, $requests[0]->getHeaders()->getAll()));
        }

        $request = null;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testFactoryUsesSpecifiedProtocol()
    {
        $request = RequestFactory::create('GET', 'http://localhost:8124/');
        $request->setProtocolVersion('1.1');
        $handle = CurlHandle::factory($request);
        $options = $request->getParams()->get('curl.last_options');
        $this->assertEquals(CURL_HTTP_VERSION_1_1, $options[CURLOPT_HTTP_VERSION]);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testUploadsPutData()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/');
        $request->setBody(EntityBody::factory('test'), 'text/plain', false);

        $o = $this->getWildcardObserver($request);
        $request->send();

        // Make sure that the events were dispatched
        $this->assertTrue($o->has('curl.callback.read'));
        $this->assertTrue($o->has('curl.callback.write'));
        $this->assertTrue($o->has('curl.callback.progress'));

        // Make sure that the data was sent through the event
        $this->assertEquals('test', $o->getData('curl.callback.read', 'read'));
        $this->assertEquals('hi', $o->getData('curl.callback.write', 'write'));

        // Ensure that the request was received exactly as intended
        $r = $this->getServer()->getReceivedRequests(true);

        $this->assertEquals((string) $request, (string) $r[0]);
        $this->assertFalse($r[0]->hasHeader('Transfer-Encoding'));
        $this->assertEquals(4, $r[0]->getHeader('Content-Length'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testUploadsPutDataUsingChunkedEncodingWhenLengthCannotBeDetermined()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi"
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/');
        $request->setBody(EntityBody::factory(fopen($this->getServer()->getUrl(), 'r')), 'text/plain');
        $request->send();

        $r = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('chunked', $r[1]->getHeader('Transfer-Encoding'));
        $this->assertFalse($r[1]->hasHeader('Content-Length'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testUploadsPutDataUsingChunkedEncodingWhenForced()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/');
        $request->setBody(EntityBody::factory('hi!'), 'text/plain', true);
        $request->send();

        $r = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('chunked', $r[0]->getHeader('Transfer-Encoding'));
        $this->assertFalse($r[0]->hasHeader('Content-Length'));
        $this->assertEquals('hi!', $r[0]->getBody(true));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle
     */
    public function testSendsPostRequests()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        // Create a new request using the same connection and POST
        $request = RequestFactory::create('POST', $this->getServer()->getUrl());
        $request->setClient(new Client());
        $request->addPostFields(array(
            'a' => 'b',
            'c' => 'ay! ~This is a test, isn\'t it?'
        ));
        $request->send();

        // Make sure that the request was sent correctly
        $r = $this->getServer()->getReceivedRequests(true);

        $this->assertEquals((string) $request, (string) $r[0]);
    }
}