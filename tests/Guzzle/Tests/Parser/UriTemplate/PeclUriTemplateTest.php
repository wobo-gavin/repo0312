<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parsers\UriTemplate;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\UriTemplate\PeclUriTemplate;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\UriTemplate\PeclUriTemplate
 */
class PeclUriTemplateTest extends AbstractUriTemplateTest
{
    /**
     * @dataProvider templateProvider
     */
    public function testExpandsUriTemplates($template, $expansion, $params)
    {
        $uri = new PeclUriTemplate($template);
        $this->assertEquals($expansion, $uri->expand($template, $params));
    }
}
