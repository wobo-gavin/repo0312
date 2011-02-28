<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command;

/**
 * This message instructs Checkout By Amazon to associate a merchant-assigned
 * order number with an order. This command does not impact the order's
 * fulfillment state.
 *
 * @author Michael Dowling <michael@shoebacca.com>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ msg_type static="cmpi_add_order_number"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ transaction_type required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_id required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_number required="true"
 */
class AddOrderNumber extends Txn
{
    /**
     * Centinel generated transaction identifier.
     *
     * @param string $value Value to set
     *
     * @return AddOrderNumber
     */
    public function setOrderId($value)
    {
        return $this->set('order_id', $value);
    }

    /**
     * Set the order number that you have assigned to an order.
     *
     * @param string $value Value to set
     *
     * @return AddOrderNumber
     */
    public function setOrderNumber($value)
    {
        return $this->set('order_number', $value);
    }
}