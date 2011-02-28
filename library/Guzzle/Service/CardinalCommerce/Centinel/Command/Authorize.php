<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command;

/**
 * You can authorize a transaction even though a implicit authorization occurs
 * when a con- sumer completes transaction at Checkout By Amazon. You are not
 * required to send in this message.
 *
 * StatusCode is 'P' - Pending status if not 'Authorized'.
 * StatusCode is 'Y' - Completed status if 'Authorized'.
 *
 * @author Michael Dowling <michael@shoebacca.com>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ msg_type static="cmpi_authorize"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ transaction_type required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_id required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_description
 */
class Authorize extends Txn
{
    /**
     * Centinel generated transaction identifier.
     *
     * @param string $value Value to set
     *
     * @return Authorize
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
     * @return Authorize
     */
    public function setOrderDescription($value)
    {
        return $this->set('order_description', $value);
    }
}