<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception;

/**
 * Exception thrown during a batch transfer
 */
class BatchTransferException extends \Exception implements /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
{
    /**
     * @param array      $batch     Batch being sent when the exception occurred
     * @param \Exception $exception Exception encountered
     */
    public function __construct(array $batch, \Exception $exception)
    {
        $this->batch = $batch;
        parent::__construct('Exception encountered while transferring batch', $exception->getCode(), $exception);
    }

    /**
     * Get the batch that we being sent when the exception occurred
     *
     * @return array
     */
    public function getBatch()
    {
        return $this->batch;
    }
}
