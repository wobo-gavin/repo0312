<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Validation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Url
 */
class UrlTest extends Validation
{
    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation\Url';
        return array(
            array($c, 'foo', null, 'Value is not a valid URL', null),
            array($c, 'http://www.foo.com', null, true, null),
            array($c, 'http://michaeld:foo@www.foo.com/path?query=a&b=c#foo', null, true, null)
        );
    }
}
