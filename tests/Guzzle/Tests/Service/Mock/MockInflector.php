<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock;

class MockInflector extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inflector
{
    /**
     * Get cache information from the inflector
     *
     * @return array Returns an array containing a snake and camel key, and each
     *      value of each cache in a sub-array
     */
    public static function getCache()
    {
        return self::$cache;
    }
}
