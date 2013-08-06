<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryAggregator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryString;

/**
 * Interface used for aggregating multi-value query string parameters into a flattened array
 */
interface QueryAggregatorInterface
{
    /**
     * Aggregate a query string array into a flattened array
     *
     * @param array  $query Query string parameters
     *
     * @return array
     */
    public function aggregate(array $query);
}
