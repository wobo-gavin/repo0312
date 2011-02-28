<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Filter\PriorityFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterInterface;

/**
 * Test class for priority filter
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PriorityFilterTest extends AbstractFilterTest
{
    /**
     * @outputBuffering enabled
     */
    public function testFilter()
    {
        $this->adapter->getFilterChain()->addFilter(new PriorityFilter(array(
            'priority' => \LOG_CRIT
        )));

        $this->logger->log('Test', \LOG_INFO);

        $this->assertEquals('', ob_get_contents());

        $this->logger->log('Test', \LOG_CRIT);

        $this->assertTrue(strpos(ob_get_contents(), 'Test') !== false);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogException
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Filter\PriorityFilter::init
     */
    public function testPriorityIsRequired()
    {
        new PriorityFilter();
    }
}