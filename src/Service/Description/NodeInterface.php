<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ToArrayInterface;

/**
 * Represents a node in which all description properties implement
 */
interface NodeInterface extends ToArrayInterface
{
    /**
     * Get the name of the node
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get the documentation of the node
     *
     * @return string|null
     */
    public function getDocumentation();

    /**
     * Get extra data from the node
     *
     * @param string $name Name of the data point to retrieve or null to get all
     *
     * @return array|mixed|null
     */
    public function getMetadata($name = null);
}
