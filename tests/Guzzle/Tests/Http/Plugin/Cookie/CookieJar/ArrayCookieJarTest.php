<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin\Cookie\CookieJar;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookiePlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\CookieJarInterface;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ArrayCookieJarTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ArrayCookieJar
     */
    private $jar;

    public function setUp()
    {
        $this->jar = new ArrayCookieJar();
    }

    /**
     * Add values to the cookiejar
     */
    public static function addCookies(CookieJarInterface $jar)
    {
        $jar->save(array(
            'cookies' => array(
                'foo=bar',
                'baz=foobar'
            ),
            'domain' => 'example.com',
            'path' => '/',
            'max_age' => '86400',
            'port' => array(80, 8080),
            'version' => '1',
            'secure' => true
        ))->save(array(
            'cookies' => array(
                'test=123'
            ),
            'domain' => 'www.foobar.com',
            'path' => '/path/',
            'discard' => true
        ))->save(array(
            'domain' => '.y.example.com',
            'path' => '/acme/',
            'cookies' => array(
                'muppet=cookie_monster'
            ),
            'comment' => 'Comment goes here...',
            'expires' => /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('+1 day')
        ))->save(array(
            'domain' => '.example.com',
            'path' => '/test/acme/',
            'cookies' => array(
                'googoo=gaga'
            ),
            'max_age' => 1500,
            'version' => 2
        ));
    }

    /**
     * Provides test data for cookie jar retrieval
     */
    public function getCookiesDataProvider()
    {
        return array(
            array(array('foo', 'baz', 'test', 'muppet', 'googoo'), '', '', '', false),
            array(array('foo', 'baz', 'muppet', 'googoo'), '', '', '', true),
            array(array('googoo'), 'www.example.com', '', '', false),
            array(array('muppet', 'googoo'), 'test.y.example.com', '', '', false),
            array(array('foo', 'baz'), 'example.com', '', '', false),
            array(array('muppet'), 'x.y.example.com', '/acme/', '', false),
            array(array('muppet'), 'x.y.example.com', '/acme/test/', '', false),
            array(array('googoo'), 'x.y.example.com', '/test/acme/test/', '', false),
            array(array('foo', 'baz'), 'example.com', '', '', false),
            array(array('foo', 'baz'), 'example.com', '', 'baz', false),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     */
    public function testStoresAndRetrievesCookies()
    {
        $j = $this->jar;

        $this->assertSame($j, $j->save(array(
            'cookies' => array(
                'foo=bar',
                'baz=foobar'
            ),
            'domain' => '.example.com',
            'path' => '/',
            'max_age' => '86400',
            'port' => array(80, 8080),
            'version' => '1',
            'secure' => true
        )));

        $this->assertSame($j, $j->save(array(
            'cookies' => array(
                'test=123'
            ),
            'domain' => 'www.foobar.com',
            'path' => '/path/'
        )));

        $this->assertEquals(array(
            array (
                'cookies' => array(
                    'foo=bar',
                    'baz=foobar',
                ),
                'domain' => '.example.com',
                'path' => '/',
                'max_age' => '86400',
                'port' => array(
                    0 => 80,
                    1 => 8080,
                ),
                'version' => '1',
                'secure' => true,
                'expires' => time() + 86400,
                'comment' => NULL,
                'comment_url' => NULL,
                'discard' => NULL,
                'http_only' => false
            ),
            array (
                'cookies' => array(
                    'test=123',
                ),
                'domain' => 'www.foobar.com',
                'path' => '/path/',
                'max_age' => 0,
                'comment' => NULL,
                'comment_url' => NULL,
                'port' => array (),
                'secure' => NULL,
                'expires' => null,
                'discard' => NULL,
                'version' => null,
                'http_only' => false
            ),
       ), $j->getCookies());
    }

    /**
     * Checks if cookies (by name) are present in a cookieJar
     *
     * @param array $cookies Cookies from the cookie jar
     * @param array $names Names to check for
     *
     * @return true
     */
    protected function hasCookies(array $cookies, array $names)
    {
        $remaining = $names;
        $extra = array();
        foreach ($cookies as $cookie) {
            foreach ($cookie['cookies'] as $c) {
                $parts = explode('=', $c, 2);
                if (false !== $pos = array_search($parts[0], $remaining)) {
                    unset($remaining[$pos]);
                } else {
                    $extra[] = $c;
                }
            }
        }
        
        $this->assertTrue(count($remaining) == 0 && count($extra) == 0, 'Unmatched: ' . implode(', ', $remaining) . ' | Extra: ' . implode(', ', $extra));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     * @dataProvider getCookiesDataProvider
     */
    public function testGetCookies(array $matches, $domain = null, $path = null, $name = null, $skipDiscardable = false)
    {
        self::addCookies($this->jar);
        $cookies = $this->jar->getCookies($domain, $path, $name, $skipDiscardable);
        $this->hasCookies($cookies, $matches);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     */
    public function testClearsTemporaryCookies()
    {
        self::addCookies($this->jar);
        $this->assertEquals(1, $this->jar->clearTemporary());
        $this->hasCookies($this->jar->getCookies(), array('foo', 'baz', 'muppet', 'googoo'));

        // Doesn't clear anything out because nothing is temporary
        $this->assertEquals(0, $this->jar->clearTemporary());

        // Add an expired cookie
        $this->jar->save(array(
            'cookies' => array('data=abc'),
            'domain' => '.example.com'
        ));
        
        // Filters out expired cookies
        $this->hasCookies($this->jar->getCookies(), array('foo', 'baz', 'muppet', 'googoo', 'data'));
        
        // Removes the expired cookie
        $this->assertEquals(1, $this->jar->clearTemporary());
        $this->hasCookies($this->jar->getCookies(), array('foo', 'baz', 'muppet', 'googoo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     */
    public function testClearsExpiredCookies()
    {
        self::addCookies($this->jar);
        $this->assertEquals(0, $this->jar->deleteExpired());

        // Add an expired cookie
        $this->jar->save(array(
            'cookies' => array('data=abc'),
            'expires' => /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('-1 day'),
            'domain' => '.example.com'
        ));

        // Filters out expired cookies
        $this->hasCookies($this->jar->getCookies(), array('foo', 'baz', 'test', 'muppet', 'googoo'));

        $this->assertEquals(1, $this->jar->deleteExpired());
        $this->assertEquals(0, $this->jar->deleteExpired());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cookies require a domain
     */
    public function testValidatesDomainWhenSaving()
    {
        $this->jar->save(array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cookies require a names and values
     */
    public function testValidatesCookiesArePresentWhenSaving()
    {
        $this->jar->save(array(
            'domain' => '.test.com'
        ));
    }

    /**
     * Provides test data for cookie jar clearing
     */
    public function clearCookiesDataProvider()
    {
        return array(
            array(array(), 4, '', '', '', ''),
            array(array('test', 'muppet', 'googoo'), 1, 'example.com', '', ''),
            array(array('foo', 'baz', 'test'), 2, 'a.y.example.com', '', ''),
            array(array('foo', 'baz', 'test', 'googoo'), 1, 'a.y.example.com', '/acme/', ''),
            // Removes only baz from the cookie that contains two values
            array(array('test', 'muppet', 'googoo', 'foo'), 1, 'example.com', '/', 'baz'),
            array(array('foo', 'baz', 'test', 'muppet'), 1, 'www.example.com', '/test/acme/', '')
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     * @dataProvider clearCookiesDataProvider
     */
    public function testClearsCookies($matches, $total, $domain, $path, $name)
    {
        self::addCookies($this->jar);
        $this->jar->clear($domain, $path, $name);
        $this->hasCookies($this->jar->getCookies(null, null, null, null, false, true), $matches);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Cookie\CookieJar\ArrayCookieJar
     */
    public function testDoesNotAddDuplicates()
    {
        $this->jar->clear();
        $this->assertEquals(0, count($this->jar->getCookies()));
        $plugin = new CookiePlugin($this->jar);
        $c = CookiePlugin::parseCookie('x=y; path=/; domain=test.com');
        $this->jar->save($c);
        $this->jar->save($c);
        $this->assertEquals(1, count($this->jar->getCookies()));
    }
}