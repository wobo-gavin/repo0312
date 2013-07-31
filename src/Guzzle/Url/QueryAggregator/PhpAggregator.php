<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryAggregator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryString;

/**
 * Aggregates nested query string variables using PHP style []
 */
class PhpAggregator implements QueryAggregatorInterface
{
    public function aggregate(array $query, $encType)
    {
        return http_build_query(
            $query,
            null,
            '&',
            $encType == QueryString::RFC3986 ? PHP_QUERY_RFC3986 : PHP_QUERY_RFC1738
        );
    }
}
