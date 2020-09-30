![/* Replaced /* Replaced Guzzle */ */](.github/logo.png?raw=true)

# /* Replaced /* Replaced Guzzle */ */, PHP HTTP /* Replaced /* Replaced client */ */

[![Latest Version](https://img.shields.io/github/release//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */.svg?style=flat-square)](https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//releases)
[![Build Status](https://img.shields.io/github/workflow/status//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//CI?label=ci%20build&style=flat-square)](https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//actions?query=workflow%3ACI)
[![Total Downloads](https://img.shields.io/packagist/dt//* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */.svg?style=flat-square)](https://packagist.org/packages//* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */)

/* Replaced /* Replaced Guzzle */ */ is a PHP HTTP /* Replaced /* Replaced client */ */ that makes it easy to send HTTP requests and
trivial to integrate with web services.

- Simple interface for building query strings, POST requests, streaming large
  uploads, streaming large downloads, using HTTP cookies, uploading JSON data,
  etc...
- Can send both synchronous and asynchronous requests using the same interface.
- Uses PSR-7 interfaces for requests, responses, and streams. This allows you
  to utilize other PSR-7 compatible libraries with /* Replaced /* Replaced Guzzle */ */.
- Supports PSR-18 allowing interoperability between other PSR-18 HTTP Clients.
- Abstracts away the underlying HTTP transport, allowing you to write
  environment and transport agnostic code; i.e., no hard dependency on cURL,
  PHP streams, sockets, or non-blocking event loops.
- Middleware system allows you to augment and compose /* Replaced /* Replaced client */ */ behavior.

```php
$/* Replaced /* Replaced client */ */ = new \/* Replaced /* Replaced Guzzle */ */Http\Client();
$response = $/* Replaced /* Replaced client */ */->request('GET', 'https://api.github.com/repos//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */');

echo $response->getStatusCode(); // 200
echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
echo $response->getBody(); // '{"id": 1420053, "name": "/* Replaced /* Replaced guzzle */ */", ...}'

// Send an asynchronous request.
$request = new \/* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */\Request('GET', 'http://httpbin.org');
$promise = $/* Replaced /* Replaced client */ */->sendAsync($request)->then(function ($response) {
    echo 'I completed! ' . $response->getBody();
});

$promise->wait();
```

## Help and docs

We use GitHub issues only to discuss bugs and new features. For support please refer to:

- [Documentation](http:///* Replaced /* Replaced guzzle */ */php.org/)
- [Stack Overflow](http://stackoverflow.com/questions/tagged//* Replaced /* Replaced guzzle */ */)
- [#/* Replaced /* Replaced guzzle */ */](https://app.slack.com//* Replaced /* Replaced client */ *//T0D2S9JCT/CE6UAAKL4) channel on [PHP-HTTP Slack](http://slack.httplug.io/)
- [Gitter](https://gitter.im//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */)


## Installing /* Replaced /* Replaced Guzzle */ */

The recommended way to install /* Replaced /* Replaced Guzzle */ */ is through
[Composer](https://getcomposer.org/).

```bash
composer require /* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */
```


## Version Guidance

| Version | Status     | Packagist           | Namespace    | Repo                | Docs                | PSR-7 | PHP Version |
|---------|------------|---------------------|--------------|---------------------|---------------------|-------|-------------|
| 3.x     | EOL        | `/* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */`     | `/* Replaced /* Replaced Guzzle */ */`     | [v3][/* Replaced /* Replaced guzzle */ */-3-repo] | [v3][/* Replaced /* Replaced guzzle */ */-3-docs] | No    | >= 5.3.3    |
| 4.x     | EOL        | `/* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */` | `/* Replaced /* Replaced Guzzle */ */Http` | [v4][/* Replaced /* Replaced guzzle */ */-4-repo] | N/A                 | No    | >= 5.4      |
| 5.x     | EOL        | `/* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */` | `/* Replaced /* Replaced Guzzle */ */Http` | [v5][/* Replaced /* Replaced guzzle */ */-5-repo] | [v5][/* Replaced /* Replaced guzzle */ */-5-docs] | No    | >= 5.4      |
| 6.x     | Security fixes | `/* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */` | `/* Replaced /* Replaced Guzzle */ */Http` | [v6][/* Replaced /* Replaced guzzle */ */-6-repo] | [v6][/* Replaced /* Replaced guzzle */ */-6-docs] | Yes   | >= 5.5      |
| 7.x     | Latest     | `/* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */` | `/* Replaced /* Replaced Guzzle */ */Http` | [v7][/* Replaced /* Replaced guzzle */ */-7-repo] | [v7][/* Replaced /* Replaced guzzle */ */-7-docs] | Yes   | >= 7.2      |

[/* Replaced /* Replaced guzzle */ */-3-repo]: https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */3
[/* Replaced /* Replaced guzzle */ */-4-repo]: https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//tree/4.x
[/* Replaced /* Replaced guzzle */ */-5-repo]: https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//tree/5.3
[/* Replaced /* Replaced guzzle */ */-6-repo]: https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ *//tree/6.5
[/* Replaced /* Replaced guzzle */ */-7-repo]: https://github.com//* Replaced /* Replaced guzzle */ *///* Replaced /* Replaced guzzle */ */
[/* Replaced /* Replaced guzzle */ */-3-docs]: http:///* Replaced /* Replaced guzzle */ */3.readthedocs.org
[/* Replaced /* Replaced guzzle */ */-5-docs]: http://docs./* Replaced /* Replaced guzzle */ */php.org/en/5.3/
[/* Replaced /* Replaced guzzle */ */-6-docs]: http://docs./* Replaced /* Replaced guzzle */ */php.org/en/6.5/
[/* Replaced /* Replaced guzzle */ */-7-docs]: http://docs./* Replaced /* Replaced guzzle */ */php.org/en/latest/
