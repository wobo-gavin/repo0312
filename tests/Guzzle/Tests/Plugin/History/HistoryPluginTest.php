<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\History;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin
 */
class HistoryPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * Adds multiple requests to a plugin
     *
     * @param HistoryPlugin $h Plugin
     * @param int $num Number of requests to add
     *
     * @return array
     */
    protected function addRequests(HistoryPlugin $h, $num)
    {
        $requests = array();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost/');
        for ($i = 0; $i < $num; $i++) {
            $requests[$i] = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
            $requests[$i]->setResponse(new Response(200), true);
            $requests[$i]->send();
            $h->add($requests[$i]);
        }

        return $requests;
    }

    public function testDescribesSubscribedEvents()
    {
        $this->assertInternalType('array', HistoryPlugin::getSubscribedEvents());
    }

    public function testMaintainsLimitValue()
    {
        $h = new HistoryPlugin();
        $this->assertSame($h, $h->setLimit(10));
        $this->assertEquals(10, $h->getLimit());
    }

    public function testAddsRequests()
    {
        $h = new HistoryPlugin();
        $requests = $this->addRequests($h, 1);
        $this->assertEquals(1, count($h));
        $i = $h->getIterator();
        $this->assertEquals(1, count($i));
        $this->assertEquals($requests[0], $i[0]);
    }

    /**
     * @depends testAddsRequests
     */
    public function testMaintainsLimit()
    {
        $h = new HistoryPlugin();
        $h->setLimit(2);
        $requests = $this->addRequests($h, 3);
        $this->assertEquals(2, count($h));
        $i = 0;
        foreach ($h as $request) {
            if ($i > 0) {
                $this->assertSame($requests[$i], $request);
            }
        }
    }

    public function testReturnsLastRequest()
    {
        $h = new HistoryPlugin();
        $requests = $this->addRequests($h, 5);
        $this->assertSame(end($requests), $h->getLastRequest());
    }

    public function testReturnsLastResponse()
    {
        $h = new HistoryPlugin();
        $requests = $this->addRequests($h, 5);
        $this->assertSame(end($requests)->getResponse(), $h->getLastResponse());
    }

    public function testClearsHistory()
    {
        $h = new HistoryPlugin();
        $requests = $this->addRequests($h, 5);
        $this->assertEquals(5, count($h));
        $h->clear();
        $this->assertEquals(0, count($h));
    }

    /**
     * @depends testAddsRequests
     */
    public function testUpdatesAddRequests()
    {
        $h = new HistoryPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->setResponse(new Response(200), true);
        $request->send();

        $this->assertSame($request, $h->getLastRequest());
    }
}
