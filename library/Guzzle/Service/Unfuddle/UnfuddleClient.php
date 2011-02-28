<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * Client for interacting with the Unfuddle webservice
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" doc="API username"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ password required="true" doc="API password"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ subdomain required="true" doc="Unfuddle project subdomain"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ api_version required="true" default="v1" doc="API version"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="https" doc="HTTP protocol (http or https)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{ protocol }}://{{ subdomain }}.unfuddle.com/api/{{ api_version }}/" doc="Unfuddle API base URL"
 */
class UnfuddleClient extends Client
{
    /**
     * {@inheritdoc}
     *
     * Configures a request for use with Unfuddle
     */
    public function getRequest($httpMethod, $headers = null, $body = null)
    {
        $request = parent::getRequest($httpMethod, $headers, $body);
        $request->setHeader('Accept', 'application/xml')
                ->setAuth($this->config->get('username'), $this->config->get('password'));

        // Configure the querystring to use a path based query string
        $request->getQuery()->setPrefix('')->setFieldSeparator('/')->setValueSeparator('/');

        return $request;
    }
}