<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testExpandsTemplate()
    {
        $this->assertEquals(
            'foo/123',
            Utils::uriTemplate('foo/{bar}', ['bar' => '123'])
        );
    }

    public function noBodyProvider()
    {
        return [['get'], ['head'], ['delete']];
    }

    public function testBatchesRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $responses = [
            new Response(301, ['Location' => 'http://foo.com/bar']),
            new Response(200),
            new Response(200),
            new Response(404)
        ];
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock($responses));
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://httpbin.org/get'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://httpbin.org/put'),
        ];

        $a = $b = $c = 0;
        $result = Utils::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, [
            'before'   => function (BeforeEvent $e) use (&$a) { $a++; },
            'complete' => function (CompleteEvent $e) use (&$b) { $b++; },
            'error'    => function (ErrorEvent $e) use (&$c) { $c++; },
        ]);

        $this->assertEquals(4, $a);
        $this->assertEquals(2, $b);
        $this->assertEquals(1, $c);
        $this->assertCount(3, $result);

        // The first result is actually the second (redirect) response.
        $this->assertSame($responses[1], $result[0]);
        // The second result is a 1:1 request:response map
        $this->assertSame($responses[2], $result[1]);
        // The third entry is the 404 RequestException
        $this->assertSame($responses[3], $result[2]->getResponse());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid event format
     */
    public function testBatchValidatesTheEventFormat()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz')];
        Utils::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, ['complete' => 'foo']);
    }

    public function testJsonDecodes()
    {
        $this->assertTrue(Utils::jsonDecode('true'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to parse JSON data: JSON_ERROR_SYNTAX - Syntax error, malformed JSON
     */
    public function testJsonDecodesWithErrorMessages()
    {
        Utils::jsonDecode('!narf!');
    }
}
