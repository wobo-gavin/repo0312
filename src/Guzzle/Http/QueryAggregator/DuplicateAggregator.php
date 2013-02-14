<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryAggregator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * Does not aggregate nested query string values and allows duplicates in the resulting array
 *
 * Example: http://test.com?q=1&q=2
 */
class DuplicateAggregator implements QueryAggregatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function aggregate($key, $value, QueryString $query)
    {
        if ($query->isUrlEncoding()) {
            return array($query->encodeValue($key) => array_map(array($query, 'encodeValue'), $value));
        } else {
            return array($key => $value);
        }
    }
}
