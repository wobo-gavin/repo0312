<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\ErrorResponse;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\ErrorResponsePlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\ErrorResponseMock;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\ErrorResponsePlugin
 */
class ErrorResponsePluginTest extends \PHPUnit_Framework_TestCase
{
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    public static function tearDownAfterClass()
    {
        self::getServer()->flush();
    }

    public function setUp()
    {
        $mockError = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\ErrorResponseMock';
        $description = ServiceDescription::factory(array(
            'operations' => array(
                'works' => array(
                    'httpMethod' => 'GET',
                    'errorResponses' => array(
                        array('code' => 500, 'class' => $mockError),
                        array('code' => 503, 'reason' => 'foo', 'class' => $mockError),
                        array('code' => 200, 'reason' => 'Error!', 'class' => $mockError)
                    )
                ),
                'bad_class' => array(
                    'httpMethod' => 'GET',
                    'errorResponses' => array(
                        array('code' => 500, 'class' => 'Does\\Not\\Exist')
                    )
                ),
                'does_not_implement' => array(
                    'httpMethod' => 'GET',
                    'errorResponses' => array(
                        array('code' => 500, 'class' => __CLASS__)
                    )
                ),
                'no_errors' => array('httpMethod' => 'GET'),
                'no_class' => array(
                    'httpMethod' => 'GET',
                    'errorResponses' => array(
                        array('code' => 500)
                    )
                ),
            )
        ));
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ServerErrorResponseException
     */
    public function testSkipsWhenErrorResponsesIsNotSet()
    {
        $this->getServer()->enqueue("HTTP/1.1 500 Foo\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('no_errors')->execute();
    }

    public function testSkipsWhenErrorResponsesIsNotSetAndAllowsSuccess()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('no_errors')->execute();
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\Exception\ErrorResponseException
     * @expectedExceptionMessage Does\Not\Exist does not exist
     */
    public function testEnsuresErrorResponseExists()
    {
        $this->getServer()->enqueue("HTTP/1.1 500 Foo\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('bad_class')->execute();
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\Exception\ErrorResponseException
     * @expectedExceptionMessage must implement /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\ErrorResponseExceptionInterface
     */
    public function testEnsuresErrorResponseImplementsInterface()
    {
        $this->getServer()->enqueue("HTTP/1.1 500 Foo\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('does_not_implement')->execute();
    }

    public function testThrowsSpecificErrorResponseOnMatch()
    {
        try {
            $this->getServer()->enqueue("HTTP/1.1 500 Foo\r\nContent-Length: 0\r\n\r\n");
            $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
            $command = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('works');
            $command->execute();
            $this->fail('Exception not thrown');
        } catch (ErrorResponseMock $e) {
            $this->assertSame($command, $e->command);
            $this->assertEquals(500, $e->response->getStatusCode());
        }
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\ErrorResponseMock
     */
    public function testThrowsWhenCodeAndPhraseMatch()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 Error!\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('works')->execute();
    }

    public function testSkipsWhenReasonDoesNotMatch()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('works')->execute();
    }

    public function testSkipsWhenNoClassIsSet()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new ErrorResponsePlugin());
        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('no_class')->execute();
    }
}
