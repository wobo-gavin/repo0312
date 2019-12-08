<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use Buzz\Client\Curl;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use Http\Client\Tests\HttpClientTest;
use Nyholm\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttplugIntegrationTest extends HttpClientTest
{
    protected function createHttpAdapter(): ClientInterface
    {
        return new Client();
    }
}
