<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Fsm;

class FsmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testValidatesStateNames()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org');
        (new Fsm('foo', []))->run(new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request));
    }

    public function testTransitionsThroughStates()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org');
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $c = [];
        $fsm = new Fsm('begin', [
            'begin' => [
                'success' => 'end',
                'transition' => function (Transaction $trans) use ($t, &$c) {
                    $this->assertSame($t, $trans);
                    $c[] = 'begin';
                }
            ],
            'end' => [
                'transition' => function (Transaction $trans) use ($t, &$c) {
                    $this->assertSame($t, $trans);
                    $c[] = 'end';
                }
            ],
        ]);

        $fsm->run($t);
        $this->assertEquals(['begin', 'end'], $c);
    }

    public function testTransitionsThroughErrorStates()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org');
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $c = [];

        $fsm = new Fsm('begin', [
            'begin' => [
                'success' => 'end',
                'error'   => 'error',
                'transition' => function (Transaction $trans) use ($t, &$c) {
                    $c[] = 'begin';
                    throw new \OutOfBoundsException();
                }
            ],
            'error' => [
                'success' => 'end',
                'error'   => 'end',
                'transition' => function (Transaction $trans) use ($t, &$c) {
                    $c[] = 'error';
                    $this->assertInstanceOf('OutOfBoundsException', $t->exception);
                }
            ],
            'end' => [
                'transition' => function (Transaction $trans) use ($t, &$c) {
                    $c[] = 'end';
                }
            ],
        ]);

        $fsm->run($t);
        $this->assertEquals(['begin', 'error', 'end'], $c);
        $this->assertNull($t->exception);
    }

    public function testThrowsTerminalErrors()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org');
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);

        $fsm = new Fsm('begin', [
            'begin' => [
                'transition' => function (Transaction $trans) use ($t) {
                    throw new \OutOfBoundsException();
                }
            ]
        ]);

        try {
            $fsm->run($t);
            $this->fail('Did not throw');
        } catch (\OutOfBoundsException $e) {
            $this->assertSame($e, $t->exception);
        }
    }
}
