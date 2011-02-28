<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\Log\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var LogPlugin
     */
    private $plugin;

    public function setUp()
    {
        $this->plugin = new LogPlugin(new Logger(array(new ClosureLogAdapter(
            function($message, $priority, $category, $host) {
                echo $message . ' ' . $priority . ' ' . $category . ' ' . $host . "\n";
            }
        ))));
    }

    public function tearDown()
    {
        unset($this->plugin);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\AbstractPlugin::attach
     */
    public function testAttach()
    {
        $request = RequestFactory::getInstance()->newRequest('GET', 'http://www.google.com/');
        $this->assertTrue($this->plugin->attach($request));
        $this->assertTrue($this->plugin->isAttached($request));
        $this->assertFalse($this->plugin->attach($request));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\AbstractPlugin::detach
     */
    public function testDetach()
    {
        $request = RequestFactory::getInstance()->newRequest('GET', 'http://www.google.com/');
        $this->assertFalse($this->plugin->detach($request));
        $this->assertTrue($this->plugin->attach($request));
        $this->assertTrue($this->plugin->detach($request));
    }
}