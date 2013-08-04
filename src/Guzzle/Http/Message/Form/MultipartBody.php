<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Form;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Mimetypes;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Url\QueryString;

/**
 * Stream that when read returns bytes for a streaming multipart/form-data body
 */
class MultipartBody implements StreamInterface
{
    /** @var StreamInterface */
    private $files = [];
    private $fields;
    private $metadata = ['mode' => 'r'];
    private $size;
    private $buffer;
    private $bufferedHeaders = [];
    private $pos = 0;
    private $currentFile = 0;
    private $currentField = 0;
    private $sentLast;
    private $boundary;

    public function __construct()
    {
        $this->boundary = uniqid();
        $this->postFields = new QueryString();
    }

    /**
     * Create a MultipartBody from a request object's form fields and files
     *
     * @param RequestInterface $request Request to create from
     *
     * @return MultipartBody
     */
    public static function fromRequest(RequestInterface $request)
    {
        $body = new self();
        $body->setFields($request->getFormFields());
        foreach ($request->getFormFiles() as $file) {
            $body->addFile($file);
        }

        return $body;
    }

    public function __toString()
    {
        $buffer = '';
        if ($this->rewind()) {
            while (!$this->eof()) {
                $buffer .= $this->read(1048576);
            }
            $this->rewind();
        }

        return $buffer;
    }

    /**
     * Get the boundary
     *
     * @return string
     */
    public function getBoundary()
    {
        return $this->boundary;
    }

    /**
     * Set a specific boundary
     *
     * @param string $boundary Boundary to set
     *
     * @return self
     */
    public function setBoundary($boundary)
    {
        $this->boundary = $boundary;

        return $this;
    }

    /**
     * Set a specific field value
     *
     * @param string $name  Field name
     * @param string $value Field value
     *
     * @return self
     */
    public function setField($name, $value)
    {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * Replace all existing POST fields
     *
     * @param QueryString $fields Fields
     *
     * @return self
     */
    public function setFields(QueryString $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Remove all files from the stream
     *
     * @return self
     */
    public function clearFiles()
    {
        $this->currentFile = 0;
        $this->files = [];

        return $this;
    }

    /**
     * Add a file to the stream
     *
     * @param FormFileInterface $file Form file
     *
     * @return self
     */
    public function addFile(FormFileInterface $file)
    {
        $this->size = null;
        $this->files[] = $file;

        return $this;
    }

    public function close()
    {
        $this->fields = $this->files = [];
    }

    public function getMetadata($key = null)
    {
        return isset($this->metadata[$key]) ? $this->metadata[$key] : null;
    }

    /**
     * @throws \InvalidArgumentException When trying to change the value of "mode"
     */
    public function setMetadata($key, $value)
    {
        if ($key == 'mode') {
            throw new \InvalidArgumentException("Cannot change immutable value of stream: {$key}");
        }

        $this->metadata[$key] = $value;

        return $this;
    }

    /**
     * Casts the body to a string, then returns a PHP temp stream representation of the body
     *
     * @return resource
     */
    public function getStream()
    {
        return Stream::factory((string) $this)->getStream();
    }

    public function detachStream() {}

    public function getUri()
    {
        return false;
    }

    /**
     * The stream has reached an EOF when all of the fields and files have been read
     * {@inheritdoc}
     */
    public function eof()
    {
        return $this->currentField == count($this->fields) && $this->currentFile == count($this->files);
    }

    public function tell()
    {
        return $this->pos;
    }

    public function isReadable()
    {
        return true;
    }

    public function isWritable()
    {
        return false;
    }

    public function isLocal()
    {
        return false;
    }

    /**
     * The steam is seekable by default, but all attached files must be seekable too
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        foreach ($this->files as $file) {
            if (!$file->getContent()->isSeekable()) {
                return false;
            }
        }

        return true;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        if ($this->size === null) {
            foreach ($this->files as $file) {
                // We must be able to ascertain the size of each attached file
                if (null === ($size = $file->getContent()->getSize())) {
                    return null;
                }
                $this->size += strlen($this->getFileHeaders($file)) + $size;
            }
            foreach ($this->fields->getKeys() as $key) {
                $this->size += strlen($this->getFieldString($key));
            }
            $this->size += strlen("\r\n--{$this->boundary}--");
        }

        return $this->size;
    }

    public function read($length)
    {
        $content = '';
        if ($this->buffer && !$this->buffer->eof()) {
            $content .= $this->buffer->read($length);
        }
        if ($delta = $length - strlen($content)) {
            $content .= $this->readData($delta);
        }

        if ($content === '' && !$this->sentLast) {
            $this->sentLast = true;
            $content = "\r\n--{$this->boundary}--";
        }

        return $content;
    }

    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * @throws \BadMethodCallException
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ($offset != 0 || $whence != SEEK_SET) {
            throw new \BadMethodCallException(__CLASS__ . ' only supports seeking to byte 0');
        }

        if (!$this->isSeekable()) {
            return false;
        }

        foreach ($this->files as $file) {
            if (!$file->getContent()->rewind()) {
                throw new \RuntimeException('Rewind on multipart file failed even though it shouldn\'t have');
            }
        }

        $this->buffer = $this->sentLast = null;
        $this->pos = $this->currentField = $this->currentFile = 0;
        $this->bufferedHeaders = [];

        return true;
    }

    /**
     * @throws \BadMethodCallException
     */
    public function readLine($maxLength = null)
    {
        throw new \BadMethodCallException(__CLASS__ . ' does not support ' . __METHOD__);
    }

    /**
     * @throws \BadMethodCallException
     */
    public function write($string)
    {
        throw new \BadMethodCallException(__CLASS__ . ' does not support ' . __METHOD__);
    }

    /**
     * No data is in the read buffer, so more needs to be pulled in from fields and files
     *
     * @param int $length Amount of data to read
     *
     * @return string
     */
    private function readData($length)
    {
        $result = '';

        if ($this->currentField < count($this->fields)) {
            $result = $this->readField($length);
        }

        if ($result === '' && $this->currentFile < count($this->files)) {
            $result = $this->readFile($length);
        }

        return $result;
    }

    /**
     * Create a new stream buffer and inject form-data
     *
     * @param int $length Amount of data to read from the stream buffer
     *
     * @return string
     */
    private function readField($length)
    {
        $name = $this->fields->getKeys()[++$this->currentField - 1];
        $this->buffer = Stream::fromString($this->getFieldString($name));

        return $this->buffer->read($length);
    }

    /**
     * Read data from a POST file, fill the read buffer with any overflow
     *
     * @param int $length Amount of data to read from the file
     *
     * @return string
     */
    private function readFile($length)
    {
        $current = $this->files[$this->currentFile];

        // Got to the next file and recursively return the read value, or bail if no more data can be read
        if ($current->getContent()->eof()) {
            return ++$this->currentFile == count($this->files) ? '' : $this->readFile($length);
        }

        // If this is the start of a file, then send the headers to the read buffer
        if (!isset($this->bufferedHeaders[$this->currentFile])) {
            $this->buffer = Stream::fromString($this->getFileHeaders($current));
            $this->bufferedHeaders[$this->currentFile] = true;
        }

        // More data needs to be read to meet the limit, so pull from the file
        $content = $this->buffer ? $this->buffer->read($length) : '';
        if (($remaining = $length - strlen($content)) > 0) {
            $content .= $current->getContent()->read($remaining);
        }

        return $content;
    }

    private function getFieldString($key)
    {
        return sprintf("--%s\r\nContent-Disposition: form-data; name=\"%s\"\r\n\r\n%s\r\n",
            $this->boundary, $key, $this->fields[$key]);
    }

    private function getFileHeaders(FormFileInterface $file)
    {
        return "--{$this->boundary}\r\n" . $file->getHeaders() . "\r\n\r\n";
    }
}
