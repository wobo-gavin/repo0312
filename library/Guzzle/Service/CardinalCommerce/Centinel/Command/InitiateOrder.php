<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command;

/**
 * The Initiate Order message (cmpi_initiate_order) is used to create an order 
 * in the Centinel system that did not authenticate within Centinel.
 *
 * @author Michael Dowling <michael@shoebacca.com>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ msg_type static="cmpi_initiate_order"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ payment_processor_order_id
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ currency_code
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_number
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ order_description
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ amount
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ merchant_data
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ user_agent
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ browser_header
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ ip_address
 */
class InitiateOrder extends Txn
{
    /**
     * The third party generated order ID
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setPaymentProcessorOrderId($value)
    {
        return $this->set('payment_processor_order_id', $value);
    }

    /**
     * 3 digit numeric, ISO 4217 currency code for the sale amount.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setCurrencyCode($value)
    {
        return $this->set('currency_code', $value);
    }

    /**
     * Order Number or transaction identifier from the merchant eCommerce website.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setOrderNumber($value)
    {
        return $this->set('order_number', $value);
    }

    /**
     * Brief description of items purchased.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setOrderDescription($value)
    {
        return $this->set('order_description', $value);
    }

    /**
     * Unformatted Total transaction amount without any decimalization.
     * For example, $100.00 = 10000, $123.67 = 12367, $.99 = 99
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setAmount($value)
    {
        return $this->set('amount', $this->convertCurrency($value));
    }

    /**
     * Merchant specified data that will be returned on the response.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setMerchantData($value)
    {
        return $this->set('merchant_data', $value);
    }

    /**
     * The exact content of the HTTP user-agent header.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setUserAgent($value)
    {
        return $this->set('user_agent', $value);
    }

    /**
     * The exact content of the HTTP accept header.
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setBrowserHeader($value)
    {
        return $this->set('browser_header', $value);
    }

    /**
     * The IP Address of the consumer. Format NNN.NNN.NNN.NNN
     *
     * @param string $value Value to set
     *
     * @return InitiateOrder
     */
    public function setIpAddress($value)
    {
        return $this->set('ip_address', $value);
    }
}