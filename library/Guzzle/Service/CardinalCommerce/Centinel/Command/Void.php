<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command;

/**
 * The Void message (cmpi_void) is responsible for void an order. If an order
 * is charged it must be refunded before it can be voided.
 *
 * @author Michael Dowling <michael@shoebacca.com>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ msg_type static="cmpi_void"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ transaction_type required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_id required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_description required="true"
 */
class Void extends Txn
{
    /**
     * Centinel generated transaction identifier.
     *
     * @param string $value Value to set
     *
     * @return Void
     */
    public function setOrderId($value)
    {
        return $this->set('order_id', $value);
    }

    /**
     * Brief description of items purchased.
     *
     * @param string $value Value to set
     *
     * @return Void
     */
    public function setOrderDescription($value)
    {
        return $this->set('order_description', $value);
    }

    /**
     * Brief reason of cancel, limited to 125 characters.
     *
     * @param string $value Value to set
     *
     * @return Void
     */
    public function setReason($value)
    {
        return $this->set('reason', $value);
    }
}