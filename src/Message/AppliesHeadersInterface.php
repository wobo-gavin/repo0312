<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message;

/**
 * Applies headers to a request.
 *
 * This interface can be used with /* Replaced /* Replaced /* Replaced Guzzle */ */ */ streams to apply body specific
 * headers to a request during the PREPARE_REQUEST priority of the before event
 *
 * NOTE: a body that implements this interface will prevent a default
 * content-type from being added to a request during the before event. If you
 * want a default content-type to be added, then it will need to be done
 * manually (e.g., using {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Mimetypes}).
 */
interface AppliesHeadersInterface
{
    /**
     * Apply headers to a request appropriate for the current state of the
     * object.
     *
     * @param RequestInterface $request Request
     */
    public function applyRequestHeaders(RequestInterface $request);
}
