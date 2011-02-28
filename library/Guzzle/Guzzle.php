<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */;

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP Library information file
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class /* Replaced /* Replaced /* Replaced Guzzle */ */ */
{
    const VERSION = '0.9';

    /**
     * @var string Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ User-Agent header
     */
    protected static $userAgent;

    /**
     * Get the default User-Agent to add to HTTP headers sent through the
     * library
     *
     * @return string
     */
    public static function getDefaultUserAgent()
    {
        // @codeCoverageIgnoreStart
        if (!self::$userAgent) {
            $version = curl_version();
            self::$userAgent = sprintf('/* Replaced /* Replaced /* Replaced Guzzle */ */ *//%s (Language=PHP/%s; curl=%s; Host=%s)', /* Replaced /* Replaced /* Replaced Guzzle */ */ */::VERSION, \PHP_VERSION, $version['version'], $version['host']);
        }
        // @codeCoverageIgnoreEnd

        return self::$userAgent;
    }
    
    /**
     * Create an RFC 1123 HTTP-Date from various date values
     *
     * @param string|int $date Date to convert
     *
     * @return string
     */
    public static function getHttpDate($date)
    {
        return gmdate('D, d M Y H:i:s', (!is_numeric($date)) ? strtotime($date) : $date) . ' GMT';
    }
}