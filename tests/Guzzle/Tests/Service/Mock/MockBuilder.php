<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder;

/**
 * Mock /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Builder
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" doc="API username"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ password required="true" doc="API password"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ subdomain required="true" doc="Project subdomain"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ api_version required="true" default="v1" doc="API version"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="http" doc="HTTP protocol (http or https)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{protocol}}://127.0.0.1:8124/{{api_version}}/{{subdomain}}" doc="Unfuddle API base URL"
 */
class MockBuilder extends DefaultBuilder
{
    public function getClass()
    {
        return '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient';
    }
}