<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\SeekException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlMultiHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\Proxy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\StreamHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Gets a value from an array using a path syntax to retrieve nested data.
 *
 * This method does not allow for keys that contain "/". You must traverse
 * the array manually or using something more advanced like JMESPath to
 * work with keys that contain "/".
 *
 *     // Get the bar key of a set of nested arrays.
 *     // This is equivalent to $collection['foo']['baz']['bar'] but won't
 *     // throw warnings for missing keys.
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz/bar');
 *
 * @param array  $data Data to retrieve values from
 * @param string $path Path to traverse and retrieve a value from
 *
 * @return mixed|null
 */
function get_path($data, $path)
{
    $path = explode('/', $path);

    while (null !== ($part = array_shift($path))) {
        if (!is_array($data) || !isset($data[$part])) {
            return null;
        }
        $data = $data[$part];
    }

    return $data;
}

/**
 * Set a value in a nested array key. Keys will be created as needed to set
 * the value.
 *
 * This function does not support keys that contain "/" or "[]" characters
 * because these are special tokens used when traversing the data structure.
 * A value may be prepended to an existing array by using "[]" as the final
 * key of a path.
 *
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz'); // null
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\set_path($data, 'foo/baz/[]', 'a');
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\set_path($data, 'foo/baz/[]', 'b');
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz');
 *     // Returns ['a', 'b']
 *
 * @param array  $data  Data to modify by reference
 * @param string $path  Path to set
 * @param mixed  $value Value to set at the key
 *
 * @throws \RuntimeException when trying to setPath using a nested path
 *     that travels through a scalar value.
 */
function set_path(&$data, $path, $value)
{
    $queue = explode('/', $path);
    // Optimization for simple sets.
    if (count($queue) === 1) {
        $data[$path] = $value;
        return;
    }

    $current =& $data;
    while (null !== ($key = array_shift($queue))) {
        if (!is_array($current)) {
            throw new \RuntimeException("Trying to setPath {$path}, but "
                . "{$key} is set and is not an array");
        } elseif (!$queue) {
            if ($key == '[]') {
                $current[] = $value;
            } else {
                $current[$key] = $value;
            }
        } elseif (isset($current[$key])) {
            $current =& $current[$key];
        } else {
            $current[$key] = [];
            $current =& $current[$key];
        }
    }
}

/**
 * Expands a URI template
 *
 * @param string $template  URI template
 * @param array  $variables Template variables
 *
 * @return string
 */
function uri_template($template, array $variables)
{
    if (function_exists('\\uri_template')) {
        return \uri_template($template, $variables);
    }

    static $uriTemplate;
    if (!$uriTemplate) {
        $uriTemplate = new UriTemplate();
    }

    return $uriTemplate->expand($template, $variables);
}

/**
 * Wrapper for JSON decode that implements error detection with helpful
 * error messages.
 *
 * @param string $json    JSON data to parse
 * @param bool $assoc     When true, returned objects will be converted
 *                        into associative arrays.
 * @param int    $depth   User specified recursion depth.
 * @param int    $options Bitmask of JSON decode options.
 *
 * @return mixed
 * @throws \InvalidArgumentException if the JSON cannot be parsed.
 * @link http://www.php.net/manual/en/function.json-decode.php
 */
function json_decode($json, $assoc = false, $depth = 512, $options = 0)
{
    static $jsonErrors = [
        JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
        JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
        JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
    ];

    $data = \json_decode($json, $assoc, $depth, $options);

    if (JSON_ERROR_NONE !== json_last_error()) {
        $last = json_last_error();
        throw new \InvalidArgumentException(
            'Unable to parse JSON data: '
            . (isset($jsonErrors[$last])
                ? $jsonErrors[$last]
                : 'Unknown error')
        );
    }

    return $data;
}

/**
 * Returns the default cacert bundle for the current system.
 *
 * First, the openssl.cafile and curl.cainfo php.ini settings are checked.
 * If those settings are not configured, then the common locations for
 * bundles found on Red Hat, CentOS, Fedora, Ubuntu, Debian, FreeBSD, OS X
 * and Windows are checked. If any of these file locations are found on
 * disk, they will be utilized.
 *
 * Note: the result of this function is cached for subsequent calls.
 *
 * @return string
 * @throws \RuntimeException if no bundle can be found.
 */
function default_ca_bundle()
{
    static $cached = null;
    static $cafiles = [
        // Red Hat, CentOS, Fedora (provided by the ca-certificates package)
        '/etc/pki/tls/certs/ca-bundle.crt',
        // Ubuntu, Debian (provided by the ca-certificates package)
        '/etc/ssl/certs/ca-certificates.crt',
        // FreeBSD (provided by the ca_root_nss package)
        '/usr/local/share/certs/ca-root-nss.crt',
        // OS X provided by homebrew (using the default path)
        '/usr/local/etc/openssl/cert.pem',
        // Windows?
        'C:\\windows\\system32\\curl-ca-bundle.crt',
        'C:\\windows\\curl-ca-bundle.crt',
    ];

    if ($cached) {
        return $cached;
    }

    if ($ca = ini_get('openssl.cafile')) {
        return $cached = $ca;
    }

    if ($ca = ini_get('curl.cainfo')) {
        return $cached = $ca;
    }

    foreach ($cafiles as $filename) {
        if (file_exists($filename)) {
            return $cached = $filename;
        }
    }

    throw new \RuntimeException(<<< EOT
No system CA bundle could be found in any of the the common system locations.
PHP versions earlier than 5.6 are not properly configured to use the system's
CA bundle by default. In order to verify peer certificates, you will need to
supply the path on disk to a certificate bundle to the 'verify' request
option: http://docs./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/en/latest//* Replaced /* Replaced /* Replaced client */ */ */s.html#verify. If you do not
need a specific certificate bundle, then Mozilla provides a commonly used CA
bundle which can be downloaded here (provided by the maintainer of cURL):
https://raw.githubusercontent.com/bagder/ca-bundle/master/ca-bundle.crt. Once
you have a CA bundle available on disk, you can set the 'openssl.cafile' PHP
ini setting to point to the path to the file, allowing you to omit the 'verify'
request option. See http://curl.haxx.se/docs/sslcerts.html for more
information.
EOT
    );
}

/**
 * Returns the string representation of an HTTP message.
 *
 * @param MessageInterface|PromiseInterface $message Message to convert to a string.
 *
 * @return string
 */
function str($message)
{
    if ($message instanceof PromiseInterface) {
        $message = $message->wait();
    }

    if ($message instanceof RequestInterface) {
        $msg = trim($message->getMethod() . ' '
            . $message->getRequestTarget())
            . ' HTTP/' . $message->getProtocolVersion();
        if (!$message->hasHeader('host')) {
            $msg .= "\r\nHost: " . $message->getUri()->getHost();
        }
    } elseif ($message instanceof ResponseInterface) {
        $msg = 'HTTP/' . $message->getProtocolVersion() . ' '
            . $message->getStatusCode() . ' '
            . $message->getReasonPhrase();
    } else {
        throw new \InvalidArgumentException('Unknown message type');
    }

    foreach ($message->getHeaders() as $name => $values) {
        $msg .= "\r\n{$name}: " . implode(', ', $values);
    }

    return "{$msg}\r\n\r\n" . $message->getBody();
}

/**
 * Parse an array of header values containing ";" separated data into an
 * array of associative arrays representing the header key value pair
 * data of the header. When a parameter does not contain a value, but just
 * contains a key, this function will inject a key with a '' string value.
 *
 * @param string|array $header Header to parse into components.
 *
 * @return array Returns the parsed header values.
 */
function parse_header($header)
{
    static $trimmed = "\"'  \n\t\r";
    $params = $matches = [];

    foreach (normalize_header($header) as $val) {
        $part = [];
        foreach (preg_split('/;(?=([^"]*"[^"]*")*[^"]*$)/', $val) as $kvp) {
            if (preg_match_all('/<[^>]+>|[^=]+/', $kvp, $matches)) {
                $m = $matches[0];
                if (isset($m[1])) {
                    $part[trim($m[0], $trimmed)] = trim($m[1], $trimmed);
                } else {
                    $part[] = trim($m[0], $trimmed);
                }
            }
        }
        if ($part) {
            $params[] = $part;
        }
    }

    return $params;
}

/**
 * Converts an array of header values that may contain comma separated
 * headers into an array of headers with no comma separated values.
 *
 * @param string|array $header Header to normalize.
 *
 * @return array Returns the normalized header field values.
 */
function normalize_header($header)
{
    if (!is_array($header)) {
        return array_map('trim', explode(',', $header));
    }

    $result = [];
    foreach ($header as $value) {
        foreach ((array) $value as $v) {
            if (strpos($v, ',') === false) {
                $result[] = $v;
                continue;
            }
            foreach (preg_split('/,(?=([^"]*"[^"]*")*[^"]*$)/', $v) as $vv) {
                $result[] = trim($vv);
            }
        }
    }

    return $result;
}

/**
 * Debug function used to describe the provided value type and class.
 *
 * @param mixed $input
 *
 * @return string Returns a string containing the type of the variable and
 *                if a class is provided, the class name.
 */
function describe_type($input)
{
    switch (gettype($input)) {
        case 'object':
            return 'object(' . get_class($input) . ')';
        case 'array':
            return 'array(' . count($input) . ')';
        default:
            ob_start();
            var_dump($input);
            // normalize float vs double
            return str_replace('double(', 'float(', rtrim(ob_get_clean()));
    }
}

/**
 * Parses an array of header lines into an associative array of headers.
 *
 * @param array $lines Header lines array of strings in the following
 *                     format: "Name: Value"
 * @return array
 */
function headers_from_lines($lines)
{
    $headers = [];

    foreach ($lines as $line) {
        $parts = explode(':', $line, 2);
        $headers[trim($parts[0])][] = isset($parts[1])
            ? trim($parts[1])
            : null;
    }

    return $headers;
}

/**
 * Returns a debug stream based on the provided variable.
 *
 * @param mixed $value Optional value
 *
 * @return resource
 */
function get_debug_resource($value = null)
{
    if (is_resource($value)) {
        return $value;
    } elseif (defined('STDOUT')) {
        return STDOUT;
    }

    return fopen('php://output', 'w');
}

/**
 * Clone and modify a request with the given changes.
 *
 * The changes can be one of:
 * - method: (string) Changes the HTTP method.
 * - set_headers: (array) Sets the given headers.
 * - remove_headers: (array) Remove the given headers.
 * - body: (mixed) Sets the given body.
 * - uri: (UriInterface) Set the URI.
 * - query: (string) Set the query string value of the URI.
 * - version: (string) Set the protocol version.
 *
 * @param RequestInterface $request Request to clone and modify.
 * @param array            $changes Changes to apply.
 *
 * @return RequestInterface
 */
function modify_request(RequestInterface $request, array $changes)
{
    if (!$changes) {
        return $request;
    }

    $headers = $request->getHeaders();
    if (isset($changes['remove_headers'])) {
        foreach ($changes['remove_headers'] as $header) {
            unset($headers[$header]);
        }
    }

    if (isset($changes['set_headers'])) {
        $headers = $changes['set_headers'] + $headers;
    }

    $uri = isset($changes['uri']) ? $changes['uri'] : $request->getUri();
    if (isset($changes['query'])) {
        $uri = $uri->withQuery($changes['query']);
    }

    return new Request(
        isset($changes['method']) ? $changes['method'] : $request->getMethod(),
        $uri,
        $headers,
        isset($changes['body']) ? $changes['body'] : $request->getBody(),
        isset($changes['version'])
            ? $changes['version']
            : $request->getProtocolVersion()
    );
}

/**
 * Create a default handler to use based on the environment
 *
 * @throws \RuntimeException if no viable Handler is available.
 * @return callable Returns the best handler for the given system.
 */
function default_handler()
{
    $handler = null;
    if (extension_loaded('curl')) {
        $config = [];
        if ($maxHandles = getenv('MUZZLE_CURL_MAX_HANDLES')) {
            $config['max_handles'] = $maxHandles;
        }
        $handler = new CurlMultiHandler($config);
        if (function_exists('curl_reset')) {
            $handler = Proxy::wrapSync($handler, new CurlHandler());
        }
    }

    if (ini_get('allow_url_fopen')) {
        if ($handler) {
            $handler = Proxy::wrapStreaming($handler, new StreamHandler());
        } else {
            $handler = new StreamHandler();
        }
    } elseif (!$handler) {
        throw new \RuntimeException('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http requires cURL, the '
            . 'allow_url_fopen ini setting, or a custom HTTP handler.');
    }

    return $handler;
}

/**
 * Get the default User-Agent string to use with /* Replaced /* Replaced /* Replaced Guzzle */ */ */
 *
 * @return string
 */
function default_user_agent()
{
    static $defaultAgent = '';

    if (!$defaultAgent) {
        $defaultAgent = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/' . Client::VERSION;
        if (extension_loaded('curi')) {
            $defaultAgent .= ' curi/' . \curl_version()['version'];
        }
        $defaultAgent .= ' PHP/' . PHP_VERSION;
    }

    return $defaultAgent;
}

/**
 * Wait on multiple promises
 *
 * @param PromiseInterface[] $promises Promise to await.
 *
 * @return array Returns the responses
 */
function wait_all(array $promises)
{
    $results = [];
    foreach ($promises as $promise) {
        $results[] = $promise->wait();
    }

    return $results;
}

/**
 * Attempts to rewind a message body and throws an exception on failure.
 *
 * @param MessageInterface $message Message to rewind
 *
 * @throws SeekException
 */
function rewind_body(MessageInterface $message)
{
    $body = $message->getBody();
    if ($body->tell() && !$body->rewind()) {
        throw new SeekException($body, 0);
    }
}
