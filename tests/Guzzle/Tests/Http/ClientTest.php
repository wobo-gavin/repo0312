<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
 */
class ClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testProvidesDefaultUserAgent()
    {
        $this->assertEquals(1, preg_match('#^/* Replaced /* Replaced /* Replaced Guzzle */ */ *//.+ curl/.+ PHP/.+$#', Client::getDefaultUserAgent()));
    }

    public function testUsesDefaultDefaultOptions()
    {

    }

    public function testUsesProvidedDefaultOptions()
    {

    }

    public function testCanSpecifyBaseUrl()
    {

    }

    public function testCanSpecifyBaseUrlUriTemplate()
    {

    }

    public function testClientUsesDefaultAdapterWhenNoneIsSet()
    {

    }

    public function testCanSpecifyAdapter()
    {

    }

    public function testCanSpecifyMessageFactory()
    {

    }

    public function testAddsDefaultUserAgentHeaderWithDefaultOptions()
    {

    }

    public function testAddsDefaultUserAgentHeaderWithoutDefaultOptions()
    {

    }

    public function testProvidesConfigPathValues()
    {

    }

    public function testClientProvidesDefaultOptionPath()
    {

    }

    public function testClientProvidesMethodShortcuts()
    {

    }

    public function testClientMergesDefaultOptionsWithRequestOptions()
    {

    }

    public function testCreatedRequestsUseCloneOfClientEventDispatcher()
    {

    }

    public function testUsesBaseUrlWhenNoUrlIsSet()
    {

    }

    public function testUsesBaseUrlCombinedWithProvidedUrl()
    {

    }

    public function testSettingAbsoluteUrlOverridesBaseUrl()
    {

    }

    public function testEmitsCreateRequestEvent()
    {

    }

    public function testClientSendsRequests()
    {

    }

    public function testSendingRequestCanBeIntercepted()
    {

    }
}
