<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException;

class ExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException
     */
    public function testRequestException()
    {
        $e = new RequestException('Message');
        $request = new Request('GET', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $e->setRequest($request);
        $this->assertEquals($request, $e->getRequest());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testBadResponseException()
    {
        $e = new BadResponseException('Message');
        $response = new Response(200);
        $e->setResponse($response);
        $this->assertEquals($response, $e->getResponse());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException::factory
     */
    public function testCreatesGenericErrorExceptionOnError()
    {
        $request = new Request('GET', 'http://www.example.com');
        $response = new Response(307);
        $e = BadResponseException::factory($request, $response);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException', $e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException::factory
     */
    public function testCreatesClientErrorExceptionOnClientError()
    {
        $request = new Request('GET', 'http://www.example.com');
        $response = new Response(404);
        $e = BadResponseException::factory($request, $response);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ClientErrorResponseException', $e);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException::factory
     */
    public function testCreatesServerErrorExceptionOnServerError()
    {
        $request = new Request('GET', 'http://www.example.com');
        $response = new Response(503);
        $e = BadResponseException::factory($request, $response);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ServerErrorResponseException', $e);
    }
}
