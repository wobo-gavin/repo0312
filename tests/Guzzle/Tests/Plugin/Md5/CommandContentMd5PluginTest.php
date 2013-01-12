<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Md5;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5\CommandContentMd5Plugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5\CommandContentMd5Plugin
 */
class CommandContentMd5PluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getClient()
    {
        $description = new ServiceDescription(array(
            'operations' => array(
                'test' => array(
                    'httpMethod' => 'PUT',
                    'parameters' => array(
                        'ContentMD5' => array(),
                        'Body'       => array(
                            'location' => 'body'
                        )
                    )
                )
            )
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    public function testHasEvents()
    {
        $this->assertNotEmpty(CommandContentMd5Plugin::getSubscribedEvents());
    }

    public function testValidatesMd5WhenParamExists()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test', array(
            'Body'       => 'Foo',
            'ContentMD5' => true
        ));
        $event = new Event(array('command' => $command));
        $request = $command->prepare();
        $plugin = new CommandContentMd5Plugin();
        $plugin->onCommandBeforeSend($event);
        $this->assertEquals('E1bGfXrRY42Ba/uCLdLCXQ==', (string) $request->getHeader('Content-MD5'));
    }

    public function testDoesNothingWhenNoPayloadExists()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription()->getOperation('test')->setHttpMethod('GET');
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test');
        $event = new Event(array('command' => $command));
        $request = $command->prepare();
        $plugin = new CommandContentMd5Plugin();
        $plugin->onCommandBeforeSend($event);
        $this->assertNull($request->getHeader('Content-MD5'));
    }

    public function testAddsValidationToResponsesOfContentMd5()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription()->getOperation('test')->setHttpMethod('GET');
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test', array(
            'ValidateMD5' => true
        ));
        $event = new Event(array('command' => $command));
        $request = $command->prepare();
        $plugin = new CommandContentMd5Plugin();
        $plugin->onCommandBeforeSend($event);
        $listeners = $request->getEventDispatcher()->getListeners('request.complete');
        $this->assertNotEmpty($listeners);
    }

    public function testIgnoresValidationWhenDisabled()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription()->getOperation('test')->setHttpMethod('GET');
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test', array(
            'ValidateMD5' => false
        ));
        $event = new Event(array('command' => $command));
        $request = $command->prepare();
        $plugin = new CommandContentMd5Plugin();
        $plugin->onCommandBeforeSend($event);
        $listeners = $request->getEventDispatcher()->getListeners('request.complete');
        $this->assertEmpty($listeners);
    }
}
