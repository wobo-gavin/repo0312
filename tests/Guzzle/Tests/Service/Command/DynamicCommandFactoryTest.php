<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DynamicCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ApiCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DynamicCommandFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ServiceDescription
     */
    protected $service;

    /**
     * Setup the service description
     */
    public function setUp()
    {
        $this->service = new ServiceDescription(
            'test',
            'Test service',
            'http://{{ bucket }}s3.amazonaws.com{{ key }}',
            array(
                new ApiCommand(array(
                    'name' => 'test_command',
                    'doc' => 'documentationForCommand',
                    'method' => 'HEAD',
                    'can_batch' => true,
                    'args' => array(
                        'bucket' => array(
                            'required' => true,
                            'append' => '.',
                            'location' => 'path'
                        ),
                        'key' => array(
                            'location' => 'path',
                            'prepend' => '/'
                        ),
                        'acl' => array(
                            'location' => 'query'
                        ),
                        'meta' => array(
                            'location' => 'header:X-Amz-Meta',
                            'append' => ':meta'
                        )
                    )
                )),
                new ApiCommand(array(
                    'name' => 'body',
                    'doc' => 'doc',
                    'method' => 'PUT',
                    'args' => array(
                        'b' => array(
                            'required' => true,
                            'prepend' => 'begin_body::',
                            'append' => '::end_body',
                            'location' => 'body'
                        ),
                        'q' => array(
                            'location' => 'query:test'
                        ),
                        'h' => array(
                            'location' => 'header:X-Custom'
                        ),
                        'i' => array(
                            'static' => 'test',
                            'location' => 'query'
                        ),
                        // Data locations means the argument is just a placeholder for data
                        // that can be referenced by other arguments
                        'data' => array(
                            'location' => 'data'
                        )
                    )
                ))
            )
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DynamicCommandFactory
     */
    public function testBuildsUsingPathParametersAndAppendSlashPrepend()
    {
        $factory = new DynamicCommandFactory($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(), $this->service, $factory);

        $command = $factory->buildCommand('test_command', array(
            'bucket' => 'test',
            'key' => 'key'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);

        // Ensure that the path values were injected into the path and base_url
        $this->assertEquals('/key', $request->getPath());
        $this->assertEquals('test.s3.amazonaws.com', $request->getHost());

        // Check the complete request
        $this->assertEquals(
            "HEAD /key HTTP/1.1\r\n" .
            "User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent() . "\r\n" .
            "Host: test.s3.amazonaws.com\r\n" .
            "\r\n", (string) $request);

        // Make sure the concrete command class is used
        $this->assertEquals(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\ClosureCommand',
            get_class($command)
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DynamicCommandFactory
     * @expectedException InvalidArgumentException
     */
    public function testValidatesArgs()
    {
        $factory = new DynamicCommandFactory($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(), $this->service, $factory);
        $command = $factory->buildCommand('test_command', array());
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DynamicCommandFactory
     */
    public function testUsesDifferentLocations()
    {
        $factory = new DynamicCommandFactory($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(), $this->service, $factory);
        $command = $factory->buildCommand('body', array(
            'b' => 'my-data',
            'q' => 'abc',
            'h' => 'haha'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);

        $this->assertEquals(
            "PUT /?test=abc&i=test HTTP/1.1\r\n" .
            "User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent() . "\r\n" .
            "Host: s3.amazonaws.com\r\n" .
            "X-Custom: haha\r\n" .
            "Content-Length: 29\r\n" .
            "Expect: 100-Continue\r\n" .
            "\r\n" .
            "begin_body::my-data::end_body", (string) $request);

        unset($command);
        unset($request);
        
        $command = $factory->buildCommand('body', array(
            'b' => 'my-data',
            'q' => 'abc',
            'h' => 'haha',
            'i' => 'does not change the value because it\'s static'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        
        $this->assertEquals(
            "PUT /?test=abc&i=test HTTP/1.1\r\n" .
            "User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent() . "\r\n" .
            "Host: s3.amazonaws.com\r\n" .
            "X-Custom: haha\r\n" .
            "Content-Length: 29\r\n" .
            "Expect: 100-Continue\r\n" .
            "\r\n" .
            "begin_body::my-data::end_body", (string) $request);
    }
}