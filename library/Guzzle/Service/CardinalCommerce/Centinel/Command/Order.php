<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;

/**
 * The Order message (cmpi_order) is responsible for creating an order. Once
 * completed, the order amount can be authorized and then captured at a later 
 * point in time.
 *
 * @author Michael Dowling <michael@shoebacca.com>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ msg_type static="cmpi_order"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ transaction_type required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_id required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ currency_code required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_number required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ amount required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ transaction_mode required="true"
 */
class Order extends Lookup
{
    /**
     * Centinel generated order identifier. Represents the value returned on the
     * Lookup Response message.
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setOrderId($value)
    {
        return $this->set('order_id', $value);
    }

    /**
     * Identifies Promotion Name.
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setPromotionName($value)
    {
        return $this->set('promotion_name', $value);
    }

    /**
     * Identifies Promotion Description.
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setPromotionDesc($value)
    {
        return $this->set('promotion_desc', $value);
    }

    /**
     * Identifies Promotion Amount.
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setPromotionAmount($value)
    {
        return $this->set('promotion_amount', $this->convertCurrency($value));
    }

    /**
     * Identifies Ship Method.
     *
     * One of: STANDARD, EXPEDITED, ONEDAY, TWODAY
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setShipMethod($value)
    {
        return $this->set('ship_method', $value);
    }

    /**
     * Identifies Ship Label.
     *
     * @param string $value Value to set
     *
     * @return Order
     */
    public function setShipLabel($value)
    {
        return $this->set('ship_label', $value);
    }

    /**
     * Add an item to the order
     *
     * @param array $data Item data, including the following array keys:
     *     name - Item name
     *     desc - Item description
     *     price - Unformatted price of item X transaction amount without any decimalization.
     *     qty - Number purchased
     *     sku - Item SKU
     *     tax_zmount - Unformatted tax amount
     *     ship_amount - Unformatted shipping amount
     *     ship_method (STANDARD EXPEDITED ONEDAY TWODAY)
     *     ship_label - Identifies shipping Label you want to apply to an Item.
     *     product_code - Identifies product code you want to apply to an Item. PHY - Physical
     *     promotion_name - Identifies promotion name you want to apply to an Item.
     *     promotion_desc - Identifies promotion description you want to apply to an Item.
     *     promotion_amount - Unformatted promotion amount
     *
     * @return Order
     */
    public function addProduct(array $data)
    {
        $data = new Collection($data);

        // only add if the required fields are set
        if (!$data->get('name') || !$data->get('desc') || !$data->hasKey('price') || !$data->get('qty')) {
            throw new \InvalidArgumentException('name, desc, price, and are required for each product');
        }

        $i = ++$this->itemCount;
        foreach ($data as $name => $value) {
            $name = sprintf($name, $i);
            if ($name == 'qty') {
                $name = 'Item_Quantity_' . $i;
            }
            if ($name == 'sku') {
                $name = 'Item_SKU_' . $i;
            }
            if (strpos($name, 'amount') || $name == 'price') {
                $value = $this->convertCurrency($value);
            }

            // Don't convert names that are already CamelCase
            if (strtolower($name) === $name) {
                $this->set("Item_" . ucfirst(Inflector::camel($name)) . "_{$i}", $value);
            } else {
                $this->set($name, $value);
            }
        }

        return $this;
    }
}