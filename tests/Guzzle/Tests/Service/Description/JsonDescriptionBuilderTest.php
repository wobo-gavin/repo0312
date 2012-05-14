<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\JsonDescriptionBuilder;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\JsonDescriptionBuilder
 */
class JsonDescriptionBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\JsonException
     */
    public function testThrowsErrorsOnOpenFailure()
    {
        $j = new JsonDescriptionBuilder();
        $b = @$j->build('/foo.does.not.exist');
    }

    public function testBuildsServiceDescriptions()
    {
        $j = new JsonDescriptionBuilder();
        $description = $j->build(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'test_service.json');
        $this->assertTrue($description->hasCommand('test'));
        $test = $description->getCommand('test');
        $this->assertEquals('/path', $test->getUri());
        $test = $description->getCommand('concrete');
        $this->assertEquals('/abstract', $test->getUri());
    }
}
