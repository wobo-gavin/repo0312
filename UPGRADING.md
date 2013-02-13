/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Upgrade Guide
====================

3.1 to 3.2
----------

### CurlMulti is no longer reused globally

Before 3.2, the same CurlMulti object was reused globally for each /* Replaced /* Replaced /* Replaced client */ */ */. This can cause issue where plugins added
to a single /* Replaced /* Replaced /* Replaced client */ */ */ can pollute requests dispatched from other /* Replaced /* Replaced /* Replaced client */ */ */s.

If you still wish to reuse the same CurlMulti object with each /* Replaced /* Replaced /* Replaced client */ */ */, then you can add a listener to the
ServiceBuilder's `service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */` event to inject a custom CurlMulti object into each /* Replaced /* Replaced /* Replaced client */ */ */ as it is
created.

```php
$multi = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti();
$builder = /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder::factory('/path/to/config.json');
$builder->addListener('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */', function ($event) use ($multi) {
    $event['/* Replaced /* Replaced /* Replaced client */ */ */']->setCurlMulti($multi);
}
});
```

### No default path

URLs no longer have a default path value of '/' if no path was specified.

Before:

```php
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.foo.com');
echo $request->getUrl();
// >> http://www.foo.com/
```

After:

```php
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.foo.com');
echo $request->getUrl();
// >> http://www.foo.com
```

### Less verbose BadResponseException

The exception message for `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException` no longer contains the full HTTP request and
response information. You can, however, get access to the request and response object by calling `getRequest()` or
`getResponse()` on the exception object.

2.8 to 3.x
----------

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector

Change `\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::fromConfig` to `\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::fromConfig`

**Before**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;

class YourClient extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client
{
    public static function factory($config = array())
    {
        $default = array();
        $required = array('base_url', 'username', 'api_key');
        $config = Inspector::fromConfig($config, $default, $required);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('api_key')
        );
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig($config);

        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . '/* Replaced /* Replaced /* Replaced client */ */ */.json'));

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
```

**After**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;

class YourClient extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client
{
    public static function factory($config = array())
    {
        $default = array();
        $required = array('base_url', 'username', 'api_key');
        $config = Collection::fromConfig($config, $default, $required);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('api_key')
        );
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig($config);

        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . '/* Replaced /* Replaced /* Replaced client */ */ */.json'));

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
```

### Convert XML Service Descriptions to JSON

**Before**

```xml
<?xml version="1.0" encoding="UTF-8"?>
</* Replaced /* Replaced /* Replaced client */ */ */>
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
<//* Replaced /* Replaced /* Replaced client */ */ */>
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

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription

Commands are now called Operations

**Before**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

$sd = new ServiceDescription();
$sd->getCommands();     // @returns ApiCommandInterface[]
$sd->hasCommand($name);
$sd->getCommand($name); // @returns ApiCommandInterface|null
$sd->addCommand($command); // @param ApiCommandInterface $command
```

**After**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

$sd = new ServiceDescription();
$sd->getOperations();           // @returns OperationInterface[]
$sd->hasOperation($name);
$sd->getOperation($name);       // @returns OperationInterface|null
$sd->addOperation($operation);  // @param OperationInterface $operation
```

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflection\Inflector

Namespace is now `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector`

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin

Namespace is now `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin`. Many other changes occur within this namespace and are detailed in their own sections below.

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin and /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log

Now `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log\LogPlugin` and `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log` respectively.

**Before**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;

/** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client */
$/* Replaced /* Replaced /* Replaced client */ */ */;

// $verbosity is an integer indicating desired message verbosity level
$/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new LogPlugin(new ClosureLogAdapter(function($m) { echo $m; }, $verbosity = LogPlugin::LOG_VERBOSE);
```

**After**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\MessageFormatter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log\LogPlugin;

/** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client */
$/* Replaced /* Replaced /* Replaced client */ */ */;

// $format is a string indicating desired message format -- @see MessageFormatter
$/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new LogPlugin(new ClosureLogAdapter(function($m) { echo $m; }, $format = MessageFormatter::DEBUG_FORMAT);
```

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\CurlAuthPlugin

Now `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\CurlAuth\CurlAuthPlugin`.

### /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin

Now `/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\BackoffPlugin`, and other changes.

**Before**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin;

$backoffPlugin = new ExponentialBackoffPlugin($maxRetries, array_merge(
        ExponentialBackoffPlugin::getDefaultFailureCodes(), array(429)
    ));

$/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($backoffPlugin);
```

**After**

```php
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\BackoffPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\HttpBackoffStrategy;

// Use convenient factory method instead -- see implementation for ideas of what
// you can do with chaining backoff strategies
$backoffPlugin = BackoffPlugin::getExponentialBackoff($maxRetries, array_merge(
        HttpBackoffStrategy::getDefaultFailureCodes(), array(429)
    ));
$/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($backoffPlugin);
```


### Known Issues

#### [BUG] Accept-Encoding header behavior changed unintentionally.

(See #217) (Fixed in 09daeb8c666fb44499a0646d655a8ae36456575e)

In version 2.8 setting the `Accept-Encoding` header would set the CURLOPT_ENCODING option, which permitted cURL to
properly handle gzip/deflate compressed responses from the server. In versions affected by this bug this does not happen.
See issue #217 for a workaround, or use a version containing the fix.
