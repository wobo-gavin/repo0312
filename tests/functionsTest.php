<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function noBodyProvider()
    {
        return [['get'], ['head'], ['delete']];
    }

    public function typeProvider()
    {
        return [
            ['foo', 'string(3) "foo"'],
            [true, 'bool(true)'],
            [false, 'bool(false)'],
            [10, 'int(10)'],
            [1.0, 'float(1)'],
            [new StrClass(), 'object(/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\StrClass)'],
            [['foo'], 'array(1)']
        ];
    }
    /**
     * @dataProvider typeProvider
     */
    public function testDescribesType($input, $output)
    {
        /**
         * Output may not match if Xdebug is loaded and overloading var_dump().
         *
         * @see https://xdebug.org/docs/display#overload_var_dump
         */
        if (extension_loaded('xdebug')) {
            $originalOverload =  ini_get('xdebug.overload_var_dump');
            ini_set('xdebug.overload_var_dump', 0);
        }

        try {
            self::assertSame($output, /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\describe_type($input));
        } finally {
            if (extension_loaded('xdebug')) {
                ini_set('xdebug.overload_var_dump', $originalOverload);
            }
        }
    }

    public function testParsesHeadersFromLines()
    {
        $lines = ['Foo: bar', 'Foo: baz', 'Abc: 123', 'Def: a, b'];
        self::assertSame([
            'Foo' => ['bar', 'baz'],
            'Abc' => ['123'],
            'Def' => ['a, b'],
        ], /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\headers_from_lines($lines));
    }

    public function testParsesHeadersFromLinesWithMultipleLines()
    {
        $lines = ['Foo: bar', 'Foo: baz', 'Foo: 123'];
        self::assertSame([
            'Foo' => ['bar', 'baz', '123'],
        ], /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\headers_from_lines($lines));
    }

    public function testReturnsDebugResource()
    {
        self::assertIsResource(/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\debug_resource());
    }

    public function testProvidesDefaultCaBundler()
    {
        self::assertFileExists(/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\default_ca_bundle());
    }

    public function noProxyProvider()
    {
        return [
            ['mit.edu', ['.mit.edu'], false],
            ['foo.mit.edu', ['.mit.edu'], true],
            ['mit.edu', ['mit.edu'], true],
            ['mit.edu', ['baz', 'mit.edu'], true],
            ['mit.edu', ['', '', 'mit.edu'], true],
            ['mit.edu', ['baz', '*'], true],
        ];
    }

    /**
     * @dataProvider noproxyProvider
     */
    public function testChecksNoProxyList($host, $list, $result)
    {
        self::assertSame(
            $result,
            \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\is_host_in_noproxy($host, $list)
        );
    }

    public function testEnsuresNoProxyCheckHostIsSet()
    {
        $this->expectException(\InvalidArgumentException::class);

        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\is_host_in_noproxy('', []);
    }

    public function testEncodesJson()
    {
        self::assertSame('true', \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_encode(true));
    }

    public function testEncodesJsonAndThrowsOnError()
    {
        $this->expectException(\InvalidArgumentException::class);

        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_encode("\x99");
    }

    public function testDecodesJson()
    {
        self::assertTrue(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_decode('true'));
    }

    public function testDecodesJsonAndThrowsOnError()
    {
        $this->expectException(\InvalidArgumentException::class);

        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_decode('{{]]');
    }
}

final class StrClass
{
    public function __toString()
    {
        return 'foo';
    }
}
