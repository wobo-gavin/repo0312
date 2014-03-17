=======
Streams
=======

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses stream objects to represent request and response message bodies.
These stream objects allow you to work with various types of data all using a
common interface.

HTTP messages consist of a start-line, headers, and a body. The body of an HTTP
message can be very small or extremely large. Attempting to represent the body
of a message as a string can easily consume more memory than intended because
the body must be stored completely in memory. Attempting to store the body of a
request or response in memory would preclude the use of that implementation from
being able to work with large message bodies. The StreamInterface is used in
order to hide the implementation details of where a stream of data is read from
or written to.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */'s StreamInterface exposes several methods that enable streams to be read
from, written to, and traversed effectively.

Streams expose their capabilities using three methods: ``isReadable()``,
``isWritable()``, and ``isSeekable()``. These methods can be used by stream
collaborators to determine if a stream is capable of their requirements.

Each stream instance has various capabilities: they can be read-only,
write-only, read-write, allow arbitrary random access (seeking forwards or
backwards to any location), or only allow sequential access (for example in the
case of a socket or pipe).

Creating Streams
================

The best way to create a stream is using the
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\create()`` function. This function accepts strings,
resources returned from ``fopen()``, an object that implements
``__toString()``, and an object that implements
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface``.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;

    $stream = Stream\create('string data');
    echo $stream;
    // string data
    echo $stream->read(3);
    // str
    echo $stream->getContents();
    // ing data
    var_export($stream->eof());
    // true
    var_export($stream->tell());
    // 11

.. note::

    You can use this ``create()`` function or use the static ``factory()``
    method of the ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream`` class via
    ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream::factory()``. This static factory method has the
    same method signature as the ``create()`` function. Actually, the static
    factory method is just a convenience method for the ``create()`` function.

Metadata Streams
================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ streams that implement ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\MetadataStreamInterface``
expose stream metadata through the ``getMetadata()`` method. This method
provides the data you would retrieve when calling PHP's
`stream_get_meta_data() function <http://php.net/manual/en/function.stream-get-meta-data.php>`_.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;

    $resource = fopen('/path/to/file', 'r');
    $stream = Stream\create($resource);
    echo $stream->getMetadata('uri');
    // /path/to/file
    var_export($stream->isReadable());
    // true
    var_export($stream->isWritable());
    // false
    var_export($stream->isSeekable());
    // true

.. note::

    Streams created using ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\create()`` and
    ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream::factory()`` all implement
    ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\MetadataStreamInterface``.

Stream Decorators
=================

With the small and focused interface, add custom functionality to streams is
very simple with stream decorators. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides several built-in decorators
that provide additional stream functionality.

CachingStream
-------------

The CachingStream is used to allow seeking over previously read bytes on
non-seekable streams. This can be useful when transferring a non-seekable
entity body fails due to needing to rewind the stream (for example, resulting
from a redirect). Data that is read from the remote stream will be buffered in
a PHP temp stream so that previously read bytes are cached first in memory,
then on disk.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\CachingStream;

    $original = Stream\create(fopen('http://www.google.com', 'r'));
    $stream = new CachingStream($original);

    $stream->read(1024);
    echo $stream->tell();
    // 1024

    $stream->seek(0);
    echo $stream->tell();
    // 0

LimitStream
-----------

LimitStream can be used to read a subset or slice of an existing stream object.
This can be useful for breaking a large file into smaller pieces to be sent in
chunks (e.g. Amazon S3's multipart upload API).

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\LimitStream;

    $original = Stream\create(fopen('/tmp/test.txt', 'r+'));
    echo $original->getSize();
    // >>> 1048576

    // Limit the size of the body to 1024 bytes and start reading from byte 2048
    $stream = new LimitStream($original, 1024, 2048);
    echo $stream->getSize();
    // >>> 1024
    echo $stream->tell();
    // >>> 0

NoSeekStream
------------

NoSeekStream wraps a stream and does not allow seeking.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\LimitStream;

    $original = Stream\create('foo');
    $noSeek = new NoSeekStream($original);

    echo $noSeek->read(3);
    // foo
    var_export($noSeek->isSeekable());
    // false
    $noSeek->seek(0);
    var_export($noSeek->read(3));
    // NULL

Creating Custom Decorators
--------------------------

Creating a stream decorator is very easy thanks to the
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamDecoratorTrait``. This trait provides methods that
implement ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface`` by proxying to an underlying
stream. Just ``use`` the ``StreamDecoratorTrait`` and implement your custom
methods.

For example, let's say we wanted to call a specific function each time the last
byte is read from a stream. This could be implemented by overriding the
``read()`` method.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamDecoratorTrait;

    class EofCallbackStream implements StreamInterface, MetadataStreamInterface
    {
        use StreamDecoratorTrait;

        private $callback;

        public function __construct(StreamInterface $stream, callable $callback)
        {
            $this->stream = $stream;
            $this->callback = $callback;
        }

        public function read($length)
        {
            $result = $this->stream->read($length);

            // Invoke the callback when EOF is hit.
            if ($this->eof()) {
                call_user_func($this->callback);
            }

            return $result;
        }
    }

This decorator could be added to any existing stream and used like so:

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream;

    $original = Stream\create('foo');
    $eofStream = new EofCallbackStream($original, function () {
        echo 'EOF!';
    });

    $eofStream->read(2);
    $eofStream->read(1);
    // echoes "EOF!"
    $eofStream->seek(0);
    $eofStream->read(3);
    // echoes "EOF!"
