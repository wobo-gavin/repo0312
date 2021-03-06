/* Replaced Guzzle */ Upgrade Guide
====================

6.0 to 7.0
----------

In order to take advantage of the new features of PHP, /* Replaced Guzzle */ dropped the support
of PHP 5. The minimum supported PHP version is now PHP 7.2. Type hints and return
types for functions and methods have been added wherever possible. 

Please make sure:
- You are calling a function or a method with the correct type.
- If you extend a class of /* Replaced Guzzle */; update all signatures on methods you override.

#### Other backwards compatibility breaking changes

- Class `/* Replaced Guzzle */Http\UriTemplate` is removed.
- Class `/* Replaced Guzzle */Http\Exception\SeekException` is removed.
- Classes `/* Replaced Guzzle */Http\Exception\BadResponseException`, `/* Replaced Guzzle */Http\Exception\ClientException`, 
  `/* Replaced Guzzle */Http\Exception\ServerException` can no longer be initialized with an empty
  Response as argument.
- Class `/* Replaced Guzzle */Http\Exception\ConnectException` now extends `/* Replaced Guzzle */Http\Exception\TransferException`
  instead of `/* Replaced Guzzle */Http\Exception\RequestException`.
- Function `/* Replaced Guzzle */Http\Exception\ConnectException::getResponse()` is removed.
- Function `/* Replaced Guzzle */Http\Exception\ConnectException::hasResponse()` is removed.
- Constant `/* Replaced Guzzle */Http\ClientInterface::VERSION` is removed. Added `/* Replaced Guzzle */Http\ClientInterface::MAJOR_VERSION` instead.
- Function `/* Replaced Guzzle */Http\Exception\RequestException::getResponseBodySummary` is removed.
  Use `\/* Replaced Guzzle */Http\/* Replaced Psr7 */\get_message_body_summary` as an alternative.
- Function `/* Replaced Guzzle */Http\Cookie\CookieJar::getCookieValue` is removed.
- Request option `exception` is removed. Please use `http_errors`.
- Request option `save_to` is removed. Please use `sink`.
- Pool option `pool_size` is removed. Please use `concurrency`.
- We now look for environment variables in the `$_SERVER` super global, due to thread safety issues with `getenv`. We continue to fallback to `getenv` in CLI environments, for maximum compatibility.
- The `get`, `head`, `put`, `post`, `patch`, `delete`, `getAsync`, `headAsync`, `putAsync`, `postAsync`, `patchAsync`, and `deleteAsync` methods are now implemented as genuine methods on `/* Replaced Guzzle */Http\Client`, with strong typing. The original `__call` implementation remains unchanged for now, for maximum backwards compatibility, but won't be invoked under normal operation.
- The `log` middleware will log the errors with level `error` instead of `notice` 
- Support for international domain names (IDN) is now disabled by default, and enabling it requires installing ext-intl, linked against a modern version of the C library (ICU 4.6 or higher).

#### Native functions calls

All internal native functions calls of /* Replaced Guzzle */ are now prefixed with a slash. This
change makes it impossible for method overloading by other libraries or applications.
Example:

```php
// Before:
curl_version();

// After:
\curl_version();
```

For the full diff you can check [here](https://github.com//* Replaced guzzle *///* Replaced guzzle *//compare/6.5.4..master).

5.0 to 6.0
----------

/* Replaced Guzzle */ now uses [PSR-7](https://www.php-fig.org/psr/psr-7/) for HTTP messages.
Due to the fact that these messages are immutable, this prompted a refactoring
of /* Replaced Guzzle */ to use a middleware based system rather than an event system. Any
HTTP message interaction (e.g., `/* Replaced Guzzle */Http\Message\Request`) need to be
updated to work with the new immutable PSR-7 request and response objects. Any
event listeners or subscribers need to be updated to become middleware
functions that wrap handlers (or are injected into a
`/* Replaced Guzzle */Http\HandlerStack`).

- Removed `/* Replaced Guzzle */Http\BatchResults`
- Removed `/* Replaced Guzzle */Http\Collection`
- Removed `/* Replaced Guzzle */Http\HasDataTrait`
- Removed `/* Replaced Guzzle */Http\ToArrayInterface`
- The `/* Replaced guzzle */http/streams` dependency has been removed. Stream functionality
  is now present in the `/* Replaced Guzzle */Http\/* Replaced Psr7 */` namespace provided by the
  `/* Replaced guzzle */http/psr7` package.
- /* Replaced Guzzle */ no longer uses ReactPHP promises and now uses the
  `/* Replaced guzzle */http/promises` library. We use a custom promise library for three
  significant reasons:
  1. React promises (at the time of writing this) are recursive. Promise
     chaining and promise resolution will eventually blow the stack. /* Replaced Guzzle */
     promises are not recursive as they use a sort of trampolining technique.
     Note: there has been movement in the React project to modify promises to
     no longer utilize recursion.
  2. /* Replaced Guzzle */ needs to have the ability to synchronously block on a promise to
     wait for a result. /* Replaced Guzzle */ promises allows this functionality (and does
     not require the use of recursion).
  3. Because we need to be able to wait on a result, doing so using React
     promises requires wrapping react promises with RingPHP futures. This
     overhead is no longer needed, reducing stack sizes, reducing complexity,
     and improving performance.
- `/* Replaced Guzzle */Http\Mimetypes` has been moved to a function in
  `/* Replaced Guzzle */Http\/* Replaced Psr7 */\mimetype_from_extension` and
  `/* Replaced Guzzle */Http\/* Replaced Psr7 */\mimetype_from_filename`.
- `/* Replaced Guzzle */Http\Query` and `/* Replaced Guzzle */Http\QueryParser` have been removed. Query
  strings must now be passed into request objects as strings, or provided to
  the `query` request option when creating requests with /* Replaced client */s. The `query`
  option uses PHP's `http_build_query` to convert an array to a string. If you
  need a different serialization technique, you will need to pass the query
  string in as a string. There are a couple helper functions that will make
  working with query strings easier: `/* Replaced Guzzle */Http\/* Replaced Psr7 */\parse_query` and
  `/* Replaced Guzzle */Http\/* Replaced Psr7 */\build_query`.
- /* Replaced Guzzle */ no longer has a dependency on RingPHP. Due to the use of a middleware
  system based on PSR-7, using RingPHP and it's middleware system as well adds
  more complexity than the benefits it provides. All HTTP handlers that were
  present in RingPHP have been modified to work directly with PSR-7 messages
  and placed in the `/* Replaced Guzzle */Http\Handler` namespace. This significantly reduces
  complexity in /* Replaced Guzzle */, removes a dependency, and improves performance. RingPHP
  will be maintained for /* Replaced Guzzle */ 5 support, but will no longer be a part of
  /* Replaced Guzzle */ 6.
- As /* Replaced Guzzle */ now uses a middleware based systems the event system and RingPHP
  integration has been removed. Note: while the event system has been removed,
  it is possible to add your own type of event system that is powered by the
  middleware system.
  - Removed the `Event` namespace.
  - Removed the `Subscriber` namespace.
  - Removed `Transaction` class
  - Removed `RequestFsm`
  - Removed `RingBridge`
  - `/* Replaced Guzzle */Http\Subscriber\Cookie` is now provided by
    `/* Replaced Guzzle */Http\Middleware::cookies`
  - `/* Replaced Guzzle */Http\Subscriber\HttpError` is now provided by
    `/* Replaced Guzzle */Http\Middleware::httpError`
  - `/* Replaced Guzzle */Http\Subscriber\History` is now provided by
    `/* Replaced Guzzle */Http\Middleware::history`
  - `/* Replaced Guzzle */Http\Subscriber\Mock` is now provided by
    `/* Replaced Guzzle */Http\Handler\MockHandler`
  - `/* Replaced Guzzle */Http\Subscriber\Prepare` is now provided by
    `/* Replaced Guzzle */Http\PrepareBodyMiddleware`
  - `/* Replaced Guzzle */Http\Subscriber\Redirect` is now provided by
    `/* Replaced Guzzle */Http\RedirectMiddleware`
- /* Replaced Guzzle */ now uses `Psr\Http\Message\UriInterface` (implements in
  `/* Replaced Guzzle */Http\/* Replaced Psr7 */\Uri`) for URI support. `/* Replaced Guzzle */Http\Url` is now gone.
- Static functions in `/* Replaced Guzzle */Http\Utils` have been moved to namespaced
  functions under the `/* Replaced Guzzle */Http` namespace. This requires either a Composer
  based autoloader or you to include functions.php.
- `/* Replaced Guzzle */Http\ClientInterface::getDefaultOption` has been renamed to
  `/* Replaced Guzzle */Http\ClientInterface::getConfig`.
- `/* Replaced Guzzle */Http\ClientInterface::setDefaultOption` has been removed.
- The `json` and `xml` methods of response objects has been removed. With the
  migration to strictly adhering to PSR-7 as the interface for /* Replaced Guzzle */ messages,
  adding methods to message interfaces would actually require /* Replaced Guzzle */ messages
  to extend from PSR-7 messages rather then work with them directly.

## Migrating to middleware

The change to PSR-7 unfortunately required significant refactoring to /* Replaced Guzzle */
due to the fact that PSR-7 messages are immutable. /* Replaced Guzzle */ 5 relied on an event
system from plugins. The event system relied on mutability of HTTP messages and
side effects in order to work. With immutable messages, you have to change your
workflow to become more about either returning a value (e.g., functional
middlewares) or setting a value on an object. /* Replaced Guzzle */ v6 has chosen the
functional middleware approach.

Instead of using the event system to listen for things like the `before` event,
you now create a stack based middleware function that intercepts a request on
the way in and the promise of the response on the way out. This is a much
simpler and more predictable approach than the event system and works nicely
with PSR-7 middleware. Due to the use of promises, the middleware system is
also asynchronous.

v5:

```php
use /* Replaced Guzzle */Http\Event\BeforeEvent;
$/* Replaced client */ = new /* Replaced Guzzle */Http\Client();
// Get the emitter and listen to the before event.
$/* Replaced client */->getEmitter()->on('before', function (BeforeEvent $e) {
    // /* Replaced Guzzle */ v5 events relied on mutation
    $e->getRequest()->setHeader('X-Foo', 'Bar');
});
```

v6:

In v6, you can modify the request before it is sent using the `mapRequest`
middleware. The idiomatic way in v6 to modify the request/response lifecycle is
to setup a handler middleware stack up front and inject the handler into a
/* Replaced client */.

```php
use /* Replaced Guzzle */Http\Middleware;
// Create a handler stack that has all of the default middlewares attached
$handler = /* Replaced Guzzle */Http\HandlerStack::create();
// Push the handler onto the handler stack
$handler->push(Middleware::mapRequest(function (RequestInterface $request) {
    // Notice that we have to return a request object
    return $request->withHeader('X-Foo', 'Bar');
}));
// Inject the handler into the /* Replaced client */
$/* Replaced client */ = new /* Replaced Guzzle */Http\Client(['handler' => $handler]);
```

## POST Requests

This version added the [`form_params`](http:///* Replaced guzzle */.readthedocs.org/en/latest/request-options.html#form_params)
and `multipart` request options. `form_params` is an associative array of
strings or array of strings and is used to serialize an
`application/x-www-form-urlencoded` POST request. The
[`multipart`](http:///* Replaced guzzle */.readthedocs.org/en/latest/request-options.html#multipart)
option is now used to send a multipart/form-data POST request.

`/* Replaced Guzzle */Http\Post\PostFile` has been removed. Use the `multipart` option to add
POST files to a multipart/form-data request.

The `body` option no longer accepts an array to send POST requests. Please use
`multipart` or `form_params` instead.

The `base_url` option has been renamed to `base_uri`.

4.x to 5.0
----------

## Rewritten Adapter Layer

/* Replaced Guzzle */ now uses [RingPHP](http://ringphp.readthedocs.org/en/latest) to send
HTTP requests. The `adapter` option in a `/* Replaced Guzzle */Http\Client` constructor
is still supported, but it has now been renamed to `handler`. Instead of
passing a `/* Replaced Guzzle */Http\Adapter\AdapterInterface`, you must now pass a PHP
`callable` that follows the RingPHP specification.

## Removed Fluent Interfaces

[Fluent interfaces were removed](https://ocramius.github.io/blog/fluent-interfaces-are-evil/)
from the following classes:

- `/* Replaced Guzzle */Http\Collection`
- `/* Replaced Guzzle */Http\Url`
- `/* Replaced Guzzle */Http\Query`
- `/* Replaced Guzzle */Http\Post\PostBody`
- `/* Replaced Guzzle */Http\Cookie\SetCookie`

## Removed functions.php

Removed "functions.php", so that /* Replaced Guzzle */ is truly PSR-4 compliant. The following
functions can be used as replacements.

- `/* Replaced Guzzle */Http\json_decode` -> `/* Replaced Guzzle */Http\Utils::jsonDecode`
- `/* Replaced Guzzle */Http\get_path` -> `/* Replaced Guzzle */Http\Utils::getPath`
- `/* Replaced Guzzle */Http\Utils::setPath` -> `/* Replaced Guzzle */Http\set_path`
- `/* Replaced Guzzle */Http\Pool::batch` -> `/* Replaced Guzzle */Http\batch`. This function is, however,
  deprecated in favor of using `/* Replaced Guzzle */Http\Pool::batch()`.

The "procedural" global /* Replaced client */ has been removed with no replacement (e.g.,
`/* Replaced Guzzle */Http\get()`, `/* Replaced Guzzle */Http\post()`, etc.). Use a `/* Replaced Guzzle */Http\Client`
object as a replacement.

## `throwImmediately` has been removed

The concept of "throwImmediately" has been removed from exceptions and error
events. This control mechanism was used to stop a transfer of concurrent
requests from completing. This can now be handled by throwing the exception or
by cancelling a pool of requests or each outstanding future request
individually.

## headers event has been removed

Removed the "headers" event. This event was only useful for changing the
body a response once the headers of the response were known. You can implement
a similar behavior in a number of ways. One example might be to use a
FnStream that has access to the transaction being sent. For example, when the
first byte is written, you could check if the response headers match your
expectations, and if so, change the actual stream body that is being
written to.

## Updates to HTTP Messages

Removed the `asArray` parameter from
`/* Replaced Guzzle */Http\Message\MessageInterface::getHeader`. If you want to get a header
value as an array, then use the newly added `getHeaderAsArray()` method of
`MessageInterface`. This change makes the /* Replaced Guzzle */ interfaces compatible with
the PSR-7 interfaces.

3.x to 4.0
----------

## Overarching changes:

- Now requires PHP 5.4 or greater.
- No longer requires cURL to send requests.
- /* Replaced Guzzle */ no longer wraps every exception it throws. Only exceptions that are
  recoverable are now wrapped by /* Replaced Guzzle */.
- Various namespaces have been removed or renamed.
- No longer requiring the Symfony EventDispatcher. A custom event dispatcher
  based on the Symfony EventDispatcher is
  now utilized in `/* Replaced Guzzle */Http\Event\EmitterInterface` (resulting in significant
  speed and functionality improvements).

Changes per /* Replaced Guzzle */ 3.x namespace are described below.

## Batch

The `/* Replaced Guzzle */\Batch` namespace has been removed. This is best left to
third-parties to implement on top of /* Replaced Guzzle */'s core HTTP library.

## Cache

The `/* Replaced Guzzle */\Cache` namespace has been removed. (Todo: No suitable replacement
has been implemented yet, but hoping to utilize a PSR cache interface).

## Common

- Removed all of the wrapped exceptions. It's better to use the standard PHP
  library for unrecoverable exceptions.
- `FromConfigInterface` has been removed.
- `/* Replaced Guzzle */\Common\Version` has been removed. The VERSION constant can be found
  at `/* Replaced Guzzle */Http\ClientInterface::VERSION`.

### Collection

- `getAll` has been removed. Use `toArray` to convert a collection to an array.
- `inject` has been removed.
- `keySearch` has been removed.
- `getPath` no longer supports wildcard expressions. Use something better like
  JMESPath for this.
- `setPath` now supports appending to an existing array via the `[]` notation.

### Events

/* Replaced Guzzle */ no longer requires Symfony's EventDispatcher component. /* Replaced Guzzle */ now uses
`/* Replaced Guzzle */Http\Event\Emitter`.

- `Symfony\Component\EventDispatcher\EventDispatcherInterface` is replaced by
  `/* Replaced Guzzle */Http\Event\EmitterInterface`.
- `Symfony\Component\EventDispatcher\EventDispatcher` is replaced by
  `/* Replaced Guzzle */Http\Event\Emitter`.
- `Symfony\Component\EventDispatcher\Event` is replaced by
  `/* Replaced Guzzle */Http\Event\Event`, and /* Replaced Guzzle */ now has an EventInterface in
  `/* Replaced Guzzle */Http\Event\EventInterface`.
- `AbstractHasDispatcher` has moved to a trait, `HasEmitterTrait`, and
  `HasDispatcherInterface` has moved to `HasEmitterInterface`. Retrieving the
  event emitter of a request, /* Replaced client */, etc. now uses the `getEmitter` method
  rather than the `getDispatcher` method.

#### Emitter

- Use the `once()` method to add a listener that automatically removes itself
  the first time it is invoked.
- Use the `listeners()` method to retrieve a list of event listeners rather than
  the `getListeners()` method.
- Use `emit()` instead of `dispatch()` to emit an event from an emitter.
- Use `attach()` instead of `addSubscriber()` and `detach()` instead of
  `removeSubscriber()`.

```php
$mock = new Mock();
// 3.x
$request->getEventDispatcher()->addSubscriber($mock);
$request->getEventDispatcher()->removeSubscriber($mock);
// 4.x
$request->getEmitter()->attach($mock);
$request->getEmitter()->detach($mock);
```

Use the `on()` method to add a listener rather than the `addListener()` method.

```php
// 3.x
$request->getEventDispatcher()->addListener('foo', function (Event $event) { /* ... */ } );
// 4.x
$request->getEmitter()->on('foo', function (Event $event, $name) { /* ... */ } );
```

## Http

### General changes

- The cacert.pem certificate has been moved to `src/cacert.pem`.
- Added the concept of adapters that are used to transfer requests over the
  wire.
- Simplified the event system.
- Sending requests in parallel is still possible, but batching is no longer a
  concept of the HTTP layer. Instead, you must use the `complete` and `error`
  events to asynchronously manage parallel request transfers.
- `/* Replaced Guzzle */\Http\Url` has moved to `/* Replaced Guzzle */Http\Url`.
- `/* Replaced Guzzle */\Http\QueryString` has moved to `/* Replaced Guzzle */Http\Query`.
- QueryAggregators have been rewritten so that they are simply callable
  functions.
- `/* Replaced Guzzle */Http\StaticClient` has been removed. Use the functions provided in
  `functions.php` for an easy to use static /* Replaced client */ instance.
- Exceptions in `/* Replaced Guzzle */Http\Exception` have been updated to all extend from
  `/* Replaced Guzzle */Http\Exception\TransferException`.

### Client

Calling methods like `get()`, `post()`, `head()`, etc. no longer create and
return a request, but rather creates a request, sends the request, and returns
the response.

```php
// 3.0
$request = $/* Replaced client */->get('/');
$response = $request->send();

// 4.0
$response = $/* Replaced client */->get('/');

// or, to mirror the previous behavior
$request = $/* Replaced client */->createRequest('GET', '/');
$response = $/* Replaced client */->send($request);
```

`/* Replaced Guzzle */Http\ClientInterface` has changed.

- The `send` method no longer accepts more than one request. Use `sendAll` to
  send multiple requests in parallel.
- `setUserAgent()` has been removed. Use a default request option instead. You
  could, for example, do something like:
  `$/* Replaced client */->setConfig('defaults/headers/User-Agent', 'Foo/Bar ' . $/* Replaced client */::getDefaultUserAgent())`.
- `setSslVerification()` has been removed. Use default request options instead,
  like `$/* Replaced client */->setConfig('defaults/verify', true)`.

`/* Replaced Guzzle */Http\Client` has changed.

- The constructor now accepts only an associative array. You can include a
  `base_url` string or array to use a URI template as the base URL of a /* Replaced client */.
  You can also specify a `defaults` key that is an associative array of default
  request options. You can pass an `adapter` to use a custom adapter,
  `batch_adapter` to use a custom adapter for sending requests in parallel, or
  a `message_factory` to change the factory used to create HTTP requests and
  responses.
- The /* Replaced client */ no longer emits a `/* Replaced client */.create_request` event.
- Creating requests with a /* Replaced client */ no longer automatically utilize a URI
  template. You must pass an array into a creational method (e.g.,
  `createRequest`, `get`, `put`, etc.) in order to expand a URI template.

### Messages

Messages no longer have references to their counterparts (i.e., a request no
longer has a reference to it's response, and a response no loger has a
reference to its request). This association is now managed through a
`/* Replaced Guzzle */Http\Adapter\TransactionInterface` object. You can get references to
these transaction objects using request events that are emitted over the
lifecycle of a request.

#### Requests with a body

- `/* Replaced Guzzle */Http\Message\EntityEnclosingRequest` and
  `/* Replaced Guzzle */Http\Message\EntityEnclosingRequestInterface` have been removed. The
  separation between requests that contain a body and requests that do not
  contain a body has been removed, and now `/* Replaced Guzzle */Http\Message\RequestInterface`
  handles both use cases.
- Any method that previously accepts a `/* Replaced Guzzle */Http\Response` object now accept a
  `/* Replaced Guzzle */Http\Message\ResponseInterface`.
- `/* Replaced Guzzle */Http\Message\RequestFactoryInterface` has been renamed to
  `/* Replaced Guzzle */Http\Message\MessageFactoryInterface`. This interface is used to create
  both requests and responses and is implemented in
  `/* Replaced Guzzle */Http\Message\MessageFactory`.
- POST field and file methods have been removed from the request object. You
  must now use the methods made available to `/* Replaced Guzzle */Http\Post\PostBodyInterface`
  to control the format of a POST body. Requests that are created using a
  standard `/* Replaced Guzzle */Http\Message\MessageFactoryInterface` will automatically use
  a `/* Replaced Guzzle */Http\Post\PostBody` body if the body was passed as an array or if
  the method is POST and no body is provided.

```php
$request = $/* Replaced client */->createRequest('POST', '/');
$request->getBody()->setField('foo', 'bar');
$request->getBody()->addFile(new PostFile('file_key', fopen('/path/to/content', 'r')));
```

#### Headers

- `/* Replaced Guzzle */Http\Message\Header` has been removed. Header values are now simply
  represented by an array of values or as a string. Header values are returned
  as a string by default when retrieving a header value from a message. You can
  pass an optional argument of `true` to retrieve a header value as an array
  of strings instead of a single concatenated string.
- `/* Replaced Guzzle */Http\PostFile` and `/* Replaced Guzzle */Http\PostFileInterface` have been moved to
  `/* Replaced Guzzle */Http\Post`. This interface has been simplified and now allows the
  addition of arbitrary headers.
- Custom headers like `/* Replaced Guzzle */Http\Message\Header\Link` have been removed. Most
  of the custom headers are now handled separately in specific
  subscribers/plugins, and `/* Replaced Guzzle */Http\Message\HeaderValues::parseParams()` has
  been updated to properly handle headers that contain parameters (like the
  `Link` header).

#### Responses

- `/* Replaced Guzzle */Http\Message\Response::getInfo()` and
  `/* Replaced Guzzle */Http\Message\Response::setInfo()` have been removed. Use the event
  system to retrieve this type of information.
- `/* Replaced Guzzle */Http\Message\Response::getRawHeaders()` has been removed.
- `/* Replaced Guzzle */Http\Message\Response::getMessage()` has been removed.
- `/* Replaced Guzzle */Http\Message\Response::calculateAge()` and other cache specific
  methods have moved to the CacheSubscriber.
- Header specific helper functions like `getContentMd5()` have been removed.
  Just use `getHeader('Content-MD5')` instead.
- `/* Replaced Guzzle */Http\Message\Response::setRequest()` and
  `/* Replaced Guzzle */Http\Message\Response::getRequest()` have been removed. Use the event
  system to work with request and response objects as a transaction.
- `/* Replaced Guzzle */Http\Message\Response::getRedirectCount()` has been removed. Use the
  Redirect subscriber instead.
- `/* Replaced Guzzle */Http\Message\Response::isSuccessful()` and other related methods have
  been removed. Use `getStatusCode()` instead.

#### Streaming responses

Streaming requests can now be created by a /* Replaced client */ directly, returning a
`/* Replaced Guzzle */Http\Message\ResponseInterface` object that contains a body stream
referencing an open PHP HTTP stream.

```php
// 3.0
use /* Replaced Guzzle */\Stream\PhpStreamRequestFactory;
$request = $/* Replaced client */->get('/');
$factory = new PhpStreamRequestFactory();
$stream = $factory->fromRequest($request);
$data = $stream->read(1024);

// 4.0
$response = $/* Replaced client */->get('/', ['stream' => true]);
// Read some data off of the stream in the response body
$data = $response->getBody()->read(1024);
```

#### Redirects

The `configureRedirects()` method has been removed in favor of a
`allow_redirects` request option.

```php
// Standard redirects with a default of a max of 5 redirects
$request = $/* Replaced client */->createRequest('GET', '/', ['allow_redirects' => true]);

// Strict redirects with a custom number of redirects
$request = $/* Replaced client */->createRequest('GET', '/', [
    'allow_redirects' => ['max' => 5, 'strict' => true]
]);
```

#### EntityBody

EntityBody interfaces and classes have been removed or moved to
`/* Replaced Guzzle */Http\Stream`. All classes and interfaces that once required
`/* Replaced Guzzle */Http\EntityBodyInterface` now require
`/* Replaced Guzzle */Http\Stream\StreamInterface`. Creating a new body for a request no
longer uses `/* Replaced Guzzle */Http\EntityBody::factory` but now uses
`/* Replaced Guzzle */Http\Stream\Stream::factory` or even better:
`/* Replaced Guzzle */Http\Stream\create()`.

- `/* Replaced Guzzle */\Http\EntityBodyInterface` is now `/* Replaced Guzzle */Http\Stream\StreamInterface`
- `/* Replaced Guzzle */\Http\EntityBody` is now `/* Replaced Guzzle */Http\Stream\Stream`
- `/* Replaced Guzzle */\Http\CachingEntityBody` is now `/* Replaced Guzzle */Http\Stream\CachingStream`
- `/* Replaced Guzzle */\Http\ReadLimitEntityBody` is now `/* Replaced Guzzle */Http\Stream\LimitStream`
- `/* Replaced Guzzle */\Http\IoEmittyinEntityBody` has been removed.

#### Request lifecycle events

Requests previously submitted a large number of requests. The number of events
emitted over the lifecycle of a request has been significantly reduced to make
it easier to understand how to extend the behavior of a request. All events
emitted during the lifecycle of a request now emit a custom
`/* Replaced Guzzle */Http\Event\EventInterface` object that contains context providing
methods and a way in which to modify the transaction at that specific point in
time (e.g., intercept the request and set a response on the transaction).

- `request.before_send` has been renamed to `before` and now emits a
  `/* Replaced Guzzle */Http\Event\BeforeEvent`
- `request.complete` has been renamed to `complete` and now emits a
  `/* Replaced Guzzle */Http\Event\CompleteEvent`.
- `request.sent` has been removed. Use `complete`.
- `request.success` has been removed. Use `complete`.
- `error` is now an event that emits a `/* Replaced Guzzle */Http\Event\ErrorEvent`.
- `request.exception` has been removed. Use `error`.
- `request.receive.status_line` has been removed.
- `curl.callback.progress` has been removed. Use a custom `StreamInterface` to
  maintain a status update.
- `curl.callback.write` has been removed. Use a custom `StreamInterface` to
  intercept writes.
- `curl.callback.read` has been removed. Use a custom `StreamInterface` to
  intercept reads.

`headers` is a new event that is emitted after the response headers of a
request have been received before the body of the response is downloaded. This
event emits a `/* Replaced Guzzle */Http\Event\HeadersEvent`.

You can intercept a request and inject a response using the `intercept()` event
of a `/* Replaced Guzzle */Http\Event\BeforeEvent`, `/* Replaced Guzzle */Http\Event\CompleteEvent`, and
`/* Replaced Guzzle */Http\Event\ErrorEvent` event.

See: http://docs./* Replaced guzzle */php.org/en/latest/events.html

## Inflection

The `/* Replaced Guzzle */\Inflection` namespace has been removed. This is not a core concern
of /* Replaced Guzzle */.

## Iterator

The `/* Replaced Guzzle */\Iterator` namespace has been removed.

- `/* Replaced Guzzle */\Iterator\AppendIterator`, `/* Replaced Guzzle */\Iterator\ChunkedIterator`, and
  `/* Replaced Guzzle */\Iterator\MethodProxyIterator` are nice, but not a core requirement of
  /* Replaced Guzzle */ itself.
- `/* Replaced Guzzle */\Iterator\FilterIterator` is no longer needed because an equivalent
  class is shipped with PHP 5.4.
- `/* Replaced Guzzle */\Iterator\MapIterator` is not really needed when using PHP 5.5 because
  it's easier to just wrap an iterator in a generator that maps values.

For a replacement of these iterators, see https://github.com/nikic/iter

## Log

The LogPlugin has moved to https://github.com//* Replaced guzzle *//log-subscriber. The
`/* Replaced Guzzle */\Log` namespace has been removed. /* Replaced Guzzle */ now relies on
`Psr\Log\LoggerInterface` for all logging. The MessageFormatter class has been
moved to `/* Replaced Guzzle */Http\Subscriber\Log\Formatter`.

## Parser

The `/* Replaced Guzzle */\Parser` namespace has been removed. This was previously used to
make it possible to plug in custom parsers for cookies, messages, URI
templates, and URLs; however, this level of complexity is not needed in /* Replaced Guzzle */
so it has been removed.

- Cookie: Cookie parsing logic has been moved to
  `/* Replaced Guzzle */Http\Cookie\SetCookie::fromString`.
- Message: Message parsing logic for both requests and responses has been moved
  to `/* Replaced Guzzle */Http\Message\MessageFactory::fromMessage`. Message parsing is only
  used in debugging or deserializing messages, so it doesn't make sense for
  /* Replaced Guzzle */ as a library to add this level of complexity to parsing messages.
- UriTemplate: URI template parsing has been moved to
  `/* Replaced Guzzle */Http\UriTemplate`. The /* Replaced Guzzle */ library will automatically use the PECL
  URI template library if it is installed.
- Url: URL parsing is now performed in `/* Replaced Guzzle */Http\Url::fromString` (previously
  it was `/* Replaced Guzzle */\Http\Url::factory()`). If custom URL parsing is necessary,
  then developers are free to subclass `/* Replaced Guzzle */Http\Url`.

## Plugin

The `/* Replaced Guzzle */\Plugin` namespace has been renamed to `/* Replaced Guzzle */Http\Subscriber`.
Several plugins are shipping with the core /* Replaced Guzzle */ library under this namespace.

- `/* Replaced Guzzle */Http\Subscriber\Cookie`: Replaces the old CookiePlugin. Cookie jar
  code has moved to `/* Replaced Guzzle */Http\Cookie`.
- `/* Replaced Guzzle */Http\Subscriber\History`: Replaces the old HistoryPlugin.
- `/* Replaced Guzzle */Http\Subscriber\HttpError`: Throws errors when a bad HTTP response is
  received.
- `/* Replaced Guzzle */Http\Subscriber\Mock`: Replaces the old MockPlugin.
- `/* Replaced Guzzle */Http\Subscriber\Prepare`: Prepares the body of a request just before
  sending. This subscriber is attached to all requests by default.
- `/* Replaced Guzzle */Http\Subscriber\Redirect`: Replaces the RedirectPlugin.

The following plugins have been removed (third-parties are free to re-implement
these if needed):

- `/* Replaced Guzzle */Http\Plugin\Async` has been removed.
- `/* Replaced Guzzle */Http\Plugin\CurlAuth` has been removed.
- `/* Replaced Guzzle */Http\Plugin\ErrorResponse\ErrorResponsePlugin` has been removed. This
  functionality should instead be implemented with event listeners that occur
  after normal response parsing occurs in the /* Replaced guzzle *//command package.

The following plugins are not part of the core /* Replaced Guzzle */ package, but are provided
in separate repositories:

- `/* Replaced Guzzle */\Http\Plugin\BackoffPlugin` has been rewritten to be much simpler
  to build custom retry policies using simple functions rather than various
  chained classes. See: https://github.com//* Replaced guzzle *//retry-subscriber
- `/* Replaced Guzzle */\Http\Plugin\Cache\CachePlugin` has moved to
  https://github.com//* Replaced guzzle *//cache-subscriber
- `/* Replaced Guzzle */\Http\Plugin\Log\LogPlugin` has moved to
  https://github.com//* Replaced guzzle *//log-subscriber
- `/* Replaced Guzzle */\Http\Plugin\Md5\Md5Plugin` has moved to
  https://github.com//* Replaced guzzle *//message-integrity-subscriber
- `/* Replaced Guzzle */\Http\Plugin\Mock\MockPlugin` has moved to
  `/* Replaced Guzzle */Http\Subscriber\MockSubscriber`.
- `/* Replaced Guzzle */\Http\Plugin\Oauth\OauthPlugin` has moved to
  https://github.com//* Replaced guzzle *//oauth-subscriber

## Service

The service description layer of /* Replaced Guzzle */ has moved into two separate packages:

- http://github.com//* Replaced guzzle *//command Provides a high level abstraction over web
  services by representing web service operations using commands.
- http://github.com//* Replaced guzzle *///* Replaced guzzle */-services Provides an implementation of
  /* Replaced guzzle *//command that provides request serialization and response parsing using
  /* Replaced Guzzle */ service descriptions.

## Stream

Stream have moved to a separate package available at
https://github.com//* Replaced guzzle *//streams.

`/* Replaced Guzzle */\Stream\StreamInterface` has been given a large update to cleanly take
on the responsibilities of `/* Replaced Guzzle */\Http\EntityBody` and
`/* Replaced Guzzle */\Http\EntityBodyInterface` now that they have been removed. The number
of methods implemented by the `StreamInterface` has been drastically reduced to
allow developers to more easily extend and decorate stream behavior.

## Removed methods from StreamInterface

- `getStream` and `setStream` have been removed to better encapsulate streams.
- `getMetadata` and `setMetadata` have been removed in favor of
  `/* Replaced Guzzle */Http\Stream\MetadataStreamInterface`.
- `getWrapper`, `getWrapperData`, `getStreamType`, and `getUri` have all been
  removed. This data is accessible when
  using streams that implement `/* Replaced Guzzle */Http\Stream\MetadataStreamInterface`.
- `rewind` has been removed. Use `seek(0)` for a similar behavior.

## Renamed methods

- `detachStream` has been renamed to `detach`.
- `feof` has been renamed to `eof`.
- `ftell` has been renamed to `tell`.
- `readLine` has moved from an instance method to a static class method of
  `/* Replaced Guzzle */Http\Stream\Stream`.

## Metadata streams

`/* Replaced Guzzle */Http\Stream\MetadataStreamInterface` has been added to denote streams
that contain additional metadata accessible via `getMetadata()`.
`/* Replaced Guzzle */Http\Stream\StreamInterface::getMetadata` and
`/* Replaced Guzzle */Http\Stream\StreamInterface::setMetadata` have been removed.

## StreamRequestFactory

The entire concept of the StreamRequestFactory has been removed. The way this
was used in /* Replaced Guzzle */ 3 broke the actual interface of sending streaming requests
(instead of getting back a Response, you got a StreamInterface). Streaming
PHP requests are now implemented through the `/* Replaced Guzzle */Http\Adapter\StreamAdapter`.

3.6 to 3.7
----------

### Deprecations

- You can now enable E_USER_DEPRECATED warnings to see if you are using any deprecated methods.:

```php
\/* Replaced Guzzle */\Common\Version::$emitWarnings = true;
```

The following APIs and options have been marked as deprecated:

- Marked `/* Replaced Guzzle */\Http\Message\Request::isResponseBodyRepeatable()` as deprecated. Use `$request->getResponseBody()->isRepeatable()` instead.
- Marked `/* Replaced Guzzle */\Http\Message\Request::canCache()` as deprecated. Use `/* Replaced Guzzle */\Plugin\Cache\DefaultCanCacheStrategy->canCacheRequest()` instead.
- Marked `/* Replaced Guzzle */\Http\Message\Request::canCache()` as deprecated. Use `/* Replaced Guzzle */\Plugin\Cache\DefaultCanCacheStrategy->canCacheRequest()` instead.
- Marked `/* Replaced Guzzle */\Http\Message\Request::setIsRedirect()` as deprecated. Use the HistoryPlugin instead.
- Marked `/* Replaced Guzzle */\Http\Message\Request::isRedirect()` as deprecated. Use the HistoryPlugin instead.
- Marked `/* Replaced Guzzle */\Cache\CacheAdapterFactory::factory()` as deprecated
- Marked `/* Replaced Guzzle */\Service\Client::enableMagicMethods()` as deprecated. Magic methods can no longer be disabled on a /* Replaced Guzzle */\Service\Client.
- Marked `/* Replaced Guzzle */\Parser\Url\UrlParser` as deprecated. Just use PHP's `parse_url()` and percent encode your UTF-8.
- Marked `/* Replaced Guzzle */\Common\Collection::inject()` as deprecated.
- Marked `/* Replaced Guzzle */\Plugin\CurlAuth\CurlAuthPlugin` as deprecated. Use
  `$/* Replaced client */->getConfig()->setPath('request.options/auth', array('user', 'pass', 'Basic|Digest|NTLM|Any'));` or
  `$/* Replaced client */->setDefaultOption('auth', array('user', 'pass', 'Basic|Digest|NTLM|Any'));`

3.7 introduces `request.options` as a parameter for a /* Replaced client */ configuration and as an optional argument to all creational
request methods. When paired with a /* Replaced client */'s configuration settings, these options allow you to specify default settings
for various aspects of a request. Because these options make other previous configuration options redundant, several
configuration options and methods of a /* Replaced client */ and AbstractCommand have been deprecated.

- Marked `/* Replaced Guzzle */\Service\Client::getDefaultHeaders()` as deprecated. Use `$/* Replaced client */->getDefaultOption('headers')`.
- Marked `/* Replaced Guzzle */\Service\Client::setDefaultHeaders()` as deprecated. Use `$/* Replaced client */->setDefaultOption('headers/{header_name}', 'value')`.
- Marked 'request.params' for `/* Replaced Guzzle */\Http\Client` as deprecated. Use `$/* Replaced client */->setDefaultOption('params/{param_name}', 'value')`
- Marked 'command.headers', 'command.response_body' and 'command.on_complete' as deprecated for AbstractCommand. These will work through /* Replaced Guzzle */ 4.0

        $command = $/* Replaced client */->getCommand('foo', array(
            'command.headers' => array('Test' => '123'),
            'command.response_body' => '/path/to/file'
        ));

        // Should be changed to:

        $command = $/* Replaced client */->getCommand('foo', array(
            'command.request_options' => array(
                'headers' => array('Test' => '123'),
                'save_as' => '/path/to/file'
            )
        ));

### Interface changes

Additions and changes (you will need to update any implementations or subclasses you may have created):

- Added an `$options` argument to the end of the following methods of `/* Replaced Guzzle */\Http\ClientInterface`:
  createRequest, head, delete, put, patch, post, options, prepareRequest
- Added an `$options` argument to the end of `/* Replaced Guzzle */\Http\Message\Request\RequestFactoryInterface::createRequest()`
- Added an `applyOptions()` method to `/* Replaced Guzzle */\Http\Message\Request\RequestFactoryInterface`
- Changed `/* Replaced Guzzle */\Http\ClientInterface::get($uri = null, $headers = null, $body = null)` to
  `/* Replaced Guzzle */\Http\ClientInterface::get($uri = null, $headers = null, $options = array())`. You can still pass in a
  resource, string, or EntityBody into the $options parameter to specify the download location of the response.
- Changed `/* Replaced Guzzle */\Common\Collection::__construct($data)` to no longer accepts a null value for `$data` but a
  default `array()`
- Added `/* Replaced Guzzle */\Stream\StreamInterface::isRepeatable`
- Made `/* Replaced Guzzle */\Http\Client::expandTemplate` and `getUriTemplate` protected methods.

The following methods were removed from interfaces. All of these methods are still available in the concrete classes
that implement them, but you should update your code to use alternative methods:

- Removed `/* Replaced Guzzle */\Http\ClientInterface::setDefaultHeaders(). Use
  `$/* Replaced client */->getConfig()->setPath('request.options/headers/{header_name}', 'value')`. or
  `$/* Replaced client */->getConfig()->setPath('request.options/headers', array('header_name' => 'value'))` or
  `$/* Replaced client */->setDefaultOption('headers/{header_name}', 'value')`. or
  `$/* Replaced client */->setDefaultOption('headers', array('header_name' => 'value'))`.
- Removed `/* Replaced Guzzle */\Http\ClientInterface::getDefaultHeaders(). Use `$/* Replaced client */->getConfig()->getPath('request.options/headers')`.
- Removed `/* Replaced Guzzle */\Http\ClientInterface::expandTemplate()`. This is an implementation detail.
- Removed `/* Replaced Guzzle */\Http\ClientInterface::setRequestFactory()`. This is an implementation detail.
- Removed `/* Replaced Guzzle */\Http\ClientInterface::getCurlMulti()`. This is a very specific implementation detail.
- Removed `/* Replaced Guzzle */\Http\Message\RequestInterface::canCache`. Use the CachePlugin.
- Removed `/* Replaced Guzzle */\Http\Message\RequestInterface::setIsRedirect`. Use the HistoryPlugin.
- Removed `/* Replaced Guzzle */\Http\Message\RequestInterface::isRedirect`. Use the HistoryPlugin.

### Cache plugin breaking changes

- CacheKeyProviderInterface and DefaultCacheKeyProvider are no longer used. All of this logic is handled in a
  CacheStorageInterface. These two objects and interface will be removed in a future version.
- Always setting X-cache headers on cached responses
- Default cache TTLs are now handled by the CacheStorageInterface of a CachePlugin
- `CacheStorageInterface::cache($key, Response $response, $ttl = null)` has changed to `cache(RequestInterface
  $request, Response $response);`
- `CacheStorageInterface::fetch($key)` has changed to `fetch(RequestInterface $request);`
- `CacheStorageInterface::delete($key)` has changed to `delete(RequestInterface $request);`
- Added `CacheStorageInterface::purge($url)`
- `DefaultRevalidation::__construct(CacheKeyProviderInterface $cacheKey, CacheStorageInterface $cache, CachePlugin
  $plugin)` has changed to `DefaultRevalidation::__construct(CacheStorageInterface $cache,
  CanCacheStrategyInterface $canCache = null)`
- Added `RevalidationInterface::shouldRevalidate(RequestInterface $request, Response $response)`

3.5 to 3.6
----------

* Mixed casing of headers are now forced to be a single consistent casing across all values for that header.
* Messages internally use a HeaderCollection object to delegate handling case-insensitive header resolution
* Removed the whole changedHeader() function system of messages because all header changes now go through addHeader().
  For example, setHeader() first removes the header using unset on a HeaderCollection and then calls addHeader().
  Keeping the Host header and URL host in sync is now handled by overriding the addHeader method in Request.
* Specific header implementations can be created for complex headers. When a message creates a header, it uses a
  HeaderFactory which can map specific headers to specific header classes. There is now a Link header and
  CacheControl header implementation.
* Moved getLinks() from Response to just be used on a Link header object.

If you previously relied on /* Replaced Guzzle */\Http\Message\Header::raw(), then you will need to update your code to use the
HeaderInterface (e.g. toArray(), getAll(), etc.).

### Interface changes

* Removed from interface: /* Replaced Guzzle */\Http\ClientInterface::setUriTemplate
* Removed from interface: /* Replaced Guzzle */\Http\ClientInterface::setCurlMulti()
* Removed /* Replaced Guzzle */\Http\Message\Request::receivedRequestHeader() and implemented this functionality in
  /* Replaced Guzzle */\Http\Curl\RequestMediator
* Removed the optional $asString parameter from MessageInterface::getHeader(). Just cast the header to a string.
* Removed the optional $tryChunkedTransfer option from /* Replaced Guzzle */\Http\Message\EntityEnclosingRequestInterface
* Removed the $asObjects argument from /* Replaced Guzzle */\Http\Message\MessageInterface::getHeaders()

### Removed deprecated functions

* Removed /* Replaced Guzzle */\Parser\ParserRegister::get(). Use getParser()
* Removed /* Replaced Guzzle */\Parser\ParserRegister::set(). Use registerParser().

### Deprecations

* The ability to case-insensitively search for header values
* /* Replaced Guzzle */\Http\Message\Header::hasExactHeader
* /* Replaced Guzzle */\Http\Message\Header::raw. Use getAll()
* Deprecated cache control specific methods on /* Replaced Guzzle */\Http\Message\AbstractMessage. Use the CacheControl header object
  instead.

### Other changes

* All response header helper functions return a string rather than mixing Header objects and strings inconsistently
* Removed cURL blacklist support. This is no longer necessary now that Expect, Accept, etc. are managed by /* Replaced Guzzle */
  directly via interfaces
* Removed the injecting of a request object onto a response object. The methods to get and set a request still exist
  but are a no-op until removed.
* Most classes that used to require a `/* Replaced Guzzle */\Service\Command\CommandInterface` typehint now request a
  `/* Replaced Guzzle */\Service\Command\ArrayCommandInterface`.
* Added `/* Replaced Guzzle */\Http\Message\RequestInterface::startResponse()` to the RequestInterface to handle injecting a response
  on a request while the request is still being transferred
* `/* Replaced Guzzle */\Service\Command\CommandInterface` now extends from ToArrayInterface and ArrayAccess

3.3 to 3.4
----------

Base URLs of a /* Replaced client */ now follow the rules of https://tools.ietf.org/html/rfc3986#section-5.2.2 when merging URLs.

3.2 to 3.3
----------

### Response::getEtag() quote stripping removed

`/* Replaced Guzzle */\Http\Message\Response::getEtag()` no longer strips quotes around the ETag response header

### Removed `/* Replaced Guzzle */\Http\Utils`

The `/* Replaced Guzzle */\Http\Utils` class was removed. This class was only used for testing.

### Stream wrapper and type

`/* Replaced Guzzle */\Stream\Stream::getWrapper()` and `/* Replaced Guzzle */\Stream\Stream::getStreamType()` are no longer converted to lowercase.

### curl.emit_io became emit_io

Emitting IO events from a RequestMediator is now a parameter that must be set in a request's curl options using the
'emit_io' key. This was previously set under a request's parameters using 'curl.emit_io'

3.1 to 3.2
----------

### CurlMulti is no longer reused globally

Before 3.2, the same CurlMulti object was reused globally for each /* Replaced client */. This can cause issue where plugins added
to a single /* Replaced client */ can pollute requests dispatched from other /* Replaced client */s.

If you still wish to reuse the same CurlMulti object with each /* Replaced client */, then you can add a listener to the
ServiceBuilder's `service_builder.create_/* Replaced client */` event to inject a custom CurlMulti object into each /* Replaced client */ as it is
created.

```php
$multi = new /* Replaced Guzzle */\Http\Curl\CurlMulti();
$builder = /* Replaced Guzzle */\Service\Builder\ServiceBuilder::factory('/path/to/config.json');
$builder->addListener('service_builder.create_/* Replaced client */', function ($event) use ($multi) {
    $event['/* Replaced client */']->setCurlMulti($multi);
}
});
```

### No default path

URLs no longer have a default path value of '/' if no path was specified.

Before:

```php
$request = $/* Replaced client */->get('http://www.foo.com');
echo $request->getUrl();
// >> http://www.foo.com/
```

After:

```php
$request = $/* Replaced client */->get('http://www.foo.com');
echo $request->getUrl();
// >> http://www.foo.com
```

### Less verbose BadResponseException

The exception message for `/* Replaced Guzzle */\Http\Exception\BadResponseException` no longer contains the full HTTP request and
response information. You can, however, get access to the request and response object by calling `getRequest()` or
`getResponse()` on the exception object.

### Query parameter aggregation

Multi-valued query parameters are no longer aggregated using a callback function. `/* Replaced Guzzle */\Http\Query` now has a
setAggregator() method that accepts a `/* Replaced Guzzle */\Http\QueryAggregator\QueryAggregatorInterface` object. This object is
responsible for handling the aggregation of multi-valued query string variables into a flattened hash.

2.8 to 3.x
----------

### /* Replaced Guzzle */\Service\Inspector

Change `\/* Replaced Guzzle */\Service\Inspector::fromConfig` to `\/* Replaced Guzzle */\Common\Collection::fromConfig`

**Before**

```php
use /* Replaced Guzzle */\Service\Inspector;

class YourClient extends \/* Replaced Guzzle */\Service\Client
{
    public static function factory($config = array())
    {
        $default = array();
        $required = array('base_url', 'username', 'api_key');
        $config = Inspector::fromConfig($config, $default, $required);

        $/* Replaced client */ = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('api_key')
        );
        $/* Replaced client */->setConfig($config);

        $/* Replaced client */->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . '/* Replaced client */.json'));

        return $/* Replaced client */;
    }
```

**After**

```php
use /* Replaced Guzzle */\Common\Collection;

class YourClient extends \/* Replaced Guzzle */\Service\Client
{
    public static function factory($config = array())
    {
        $default = array();
        $required = array('base_url', 'username', 'api_key');
        $config = Collection::fromConfig($config, $default, $required);

        $/* Replaced client */ = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('api_key')
        );
        $/* Replaced client */->setConfig($config);

        $/* Replaced client */->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . '/* Replaced client */.json'));

        return $/* Replaced client */;
    }
```

### Convert XML Service Descriptions to JSON

**Before**

```xml
<?xml version="1.0" encoding="UTF-8"?>
</* Replaced client */>
    <commands>
        <!-- Groups -->
        <command name="list_groups" method="GET" uri="groups.json">
            <doc>Get a list of groups</doc>
        </command>
        <command name="search_groups" method="GET" uri='search.json?query="{{query}} type:group"'>
            <doc>Uses a search query to get a list of groups</doc>
            <param name="query" type="string" required="true" />
        </command>
        <command name="create_group" method="POST" uri="groups.json">
            <doc>Create a group</doc>
            <param name="data" type="array" location="body" filters="json_encode" doc="Group JSON"/>
            <param name="Content-Type" location="header" static="application/json"/>
        </command>
        <command name="delete_group" method="DELETE" uri="groups/{{id}}.json">
            <doc>Delete a group by ID</doc>
            <param name="id" type="integer" required="true"/>
        </command>
        <command name="get_group" method="GET" uri="groups/{{id}}.json">
            <param name="id" type="integer" required="true"/>
        </command>
        <command name="update_group" method="PUT" uri="groups/{{id}}.json">
            <doc>Update a group</doc>
            <param name="id" type="integer" required="true"/>
            <param name="data" type="array" location="body" filters="json_encode" doc="Group JSON"/>
            <param name="Content-Type" location="header" static="application/json"/>
        </command>
    </commands>
<//* Replaced client */>
```

**After**

```json
{
    "name":       "Zendesk REST API v2",
    "apiVersion": "2012-12-31",
    "description":"Provides access to Zendesk views, groups, tickets, ticket fields, and users",
    "operations": {
        "list_groups":  {
            "httpMethod":"GET",
            "uri":       "groups.json",
            "summary":   "Get a list of groups"
        },
        "search_groups":{
            "httpMethod":"GET",
            "uri":       "search.json?query=\"{query} type:group\"",
            "summary":   "Uses a search query to get a list of groups",
            "parameters":{
                "query":{
                    "location":   "uri",
                    "description":"Zendesk Search Query",
                    "type":       "string",
                    "required":   true
                }
            }
        },
        "create_group": {
            "httpMethod":"POST",
            "uri":       "groups.json",
            "summary":   "Create a group",
            "parameters":{
                "data":        {
                    "type":       "array",
                    "location":   "body",
                    "description":"Group JSON",
                    "filters":    "json_encode",
                    "required":   true
                },
                "Content-Type":{
                    "type":    "string",
                    "location":"header",
                    "static":  "application/json"
                }
            }
        },
        "delete_group": {
            "httpMethod":"DELETE",
            "uri":       "groups/{id}.json",
            "summary":   "Delete a group",
            "parameters":{
                "id":{
                    "location":   "uri",
                    "description":"Group to delete by ID",
                    "type":       "integer",
                    "required":   true
                }
            }
        },
        "get_group":    {
            "httpMethod":"GET",
            "uri":       "groups/{id}.json",
            "summary":   "Get a ticket",
            "parameters":{
                "id":{
                    "location":   "uri",
                    "description":"Group to get by ID",
                    "type":       "integer",
                    "required":   true
                }
            }
        },
        "update_group": {
            "httpMethod":"PUT",
            "uri":       "groups/{id}.json",
            "summary":   "Update a group",
            "parameters":{
                "id":          {
                    "location":   "uri",
                    "description":"Group to update by ID",
                    "type":       "integer",
                    "required":   true
                },
                "data":        {
                    "type":       "array",
                    "location":   "body",
                    "description":"Group JSON",
                    "filters":    "json_encode",
                    "required":   true
                },
                "Content-Type":{
                    "type":    "string",
                    "location":"header",
                    "static":  "application/json"
                }
            }
        }
}
```

### /* Replaced Guzzle */\Service\Description\ServiceDescription

Commands are now called Operations

**Before**

```php
use /* Replaced Guzzle */\Service\Description\ServiceDescription;

$sd = new ServiceDescription();
$sd->getCommands();     // @returns ApiCommandInterface[]
$sd->hasCommand($name);
$sd->getCommand($name); // @returns ApiCommandInterface|null
$sd->addCommand($command); // @param ApiCommandInterface $command
```

**After**

```php
use /* Replaced Guzzle */\Service\Description\ServiceDescription;

$sd = new ServiceDescription();
$sd->getOperations();           // @returns OperationInterface[]
$sd->hasOperation($name);
$sd->getOperation($name);       // @returns OperationInterface|null
$sd->addOperation($operation);  // @param OperationInterface $operation
```

### /* Replaced Guzzle */\Common\Inflection\Inflector

Namespace is now `/* Replaced Guzzle */\Inflection\Inflector`

### /* Replaced Guzzle */\Http\Plugin

Namespace is now `/* Replaced Guzzle */\Plugin`. Many other changes occur within this namespace and are detailed in their own sections below.

### /* Replaced Guzzle */\Http\Plugin\LogPlugin and /* Replaced Guzzle */\Common\Log

Now `/* Replaced Guzzle */\Plugin\Log\LogPlugin` and `/* Replaced Guzzle */\Log` respectively.

**Before**

```php
use /* Replaced Guzzle */\Common\Log\ClosureLogAdapter;
use /* Replaced Guzzle */\Http\Plugin\LogPlugin;

/** @var \/* Replaced Guzzle */\Http\Client */
$/* Replaced client */;

// $verbosity is an integer indicating desired message verbosity level
$/* Replaced client */->addSubscriber(new LogPlugin(new ClosureLogAdapter(function($m) { echo $m; }, $verbosity = LogPlugin::LOG_VERBOSE);
```

**After**

```php
use /* Replaced Guzzle */\Log\ClosureLogAdapter;
use /* Replaced Guzzle */\Log\MessageFormatter;
use /* Replaced Guzzle */\Plugin\Log\LogPlugin;

/** @var \/* Replaced Guzzle */\Http\Client */
$/* Replaced client */;

// $format is a string indicating desired message format -- @see MessageFormatter
$/* Replaced client */->addSubscriber(new LogPlugin(new ClosureLogAdapter(function($m) { echo $m; }, $format = MessageFormatter::DEBUG_FORMAT);
```

### /* Replaced Guzzle */\Http\Plugin\CurlAuthPlugin

Now `/* Replaced Guzzle */\Plugin\CurlAuth\CurlAuthPlugin`.

### /* Replaced Guzzle */\Http\Plugin\ExponentialBackoffPlugin

Now `/* Replaced Guzzle */\Plugin\Backoff\BackoffPlugin`, and other changes.

**Before**

```php
use /* Replaced Guzzle */\Http\Plugin\ExponentialBackoffPlugin;

$backoffPlugin = new ExponentialBackoffPlugin($maxRetries, array_merge(
        ExponentialBackoffPlugin::getDefaultFailureCodes(), array(429)
    ));

$/* Replaced client */->addSubscriber($backoffPlugin);
```

**After**

```php
use /* Replaced Guzzle */\Plugin\Backoff\BackoffPlugin;
use /* Replaced Guzzle */\Plugin\Backoff\HttpBackoffStrategy;

// Use convenient factory method instead -- see implementation for ideas of what
// you can do with chaining backoff strategies
$backoffPlugin = BackoffPlugin::getExponentialBackoff($maxRetries, array_merge(
        HttpBackoffStrategy::getDefaultFailureCodes(), array(429)
    ));
$/* Replaced client */->addSubscriber($backoffPlugin);
```

### Known Issues

#### [BUG] Accept-Encoding header behavior changed unintentionally.

(See #217) (Fixed in 09daeb8c666fb44499a0646d655a8ae36456575e)

In version 2.8 setting the `Accept-Encoding` header would set the CURLOPT_ENCODING option, which permitted cURL to
properly handle gzip/deflate compressed responses from the server. In versions affected by this bug this does not happen.
See issue #217 for a workaround, or use a version containing the fix.
