<?php

namespace Interop\Http\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

abstract class StreamFactoryTestCase extends TestCase
{
    use StreamHelper;

    /**
     * @var StreamFactoryInterface
     */
    protected $factory;

    /**
     * @return StreamFactoryInterface
     */
    abstract protected function createStreamFactory();

    public function setUp()
    {
        $this->factory = $this->createStreamFactory();
    }

    protected function assertStream($stream, $content)
    {
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame($content, (string) $stream);
    }

    public function testCreateStream()
    {
        $string = 'would you like some crumpets?';

        $stream = $this->factory->createStream($string);

        $this->assertStream($stream, $string);
    }

    public function testCreateStreamFromFile()
    {
        $string = 'would you like some crumpets?';
        $filename = $this->createTemporaryFile();

        file_put_contents($filename, $string);

        $stream = $this->factory->createStreamFromFile($filename);

        $this->assertStream($stream, $string);
    }

    public function testCreateStreamFromResource()
    {
        $string = 'would you like some crumpets?';
        $resource = $this->createTemporaryResource($string);

        $stream = $this->factory->createStreamFromResource($resource);

        $this->assertStream($stream, $string);
    }

    public function testCreateStreamTell()
    {
        $string = 'would you like some crumpets?';

        $stream = $this->factory->createStream($string);

        $this->assertSame(0, $stream->tell(), 'Tell on Stream from String.');
        $this->assertSame(29, $stream->tell(), 'Tell on Stream from String.');
    }

    public function testCreateStreamFromFileTell()
    {
        $string = 'would you like some crumpets?';
        $filename = $this->createTemporaryFile();

        file_put_contents($filename, $string);

        $stream = $this->factory->createStreamFromFile($filename);

        $this->assertSame(0, $stream->tell(), 'Tell on Stream from File.');
    }

    public function testCreateStreamFromResourceTell()
    {
        $string = 'would you like some crumpets?';
        $halflength = \intdiv(\strlen($string), 2);
        $resource1 = $this->createTemporaryResource($string);
        \fseek($resource1, 0, \SEEK_SET);
        $resource2 = $this->createTemporaryResource($string);
        \fseek($resource2, $halflength, \SEEK_SET);
        $resource3 = $this->createTemporaryResource($string);
        \fseek($resource3, 0, \SEEK_END);

        $stream1 = $this->factory->createStreamFromResource($resource1);
        $stream2 = $this->factory->createStreamFromResource($resource2);
        $stream3 = $this->factory->createStreamFromResource($resource3);

        $this->assertSame(0, $stream1->tell(), 'Tell at start of Stream from Resource.');
        $this->assertSame($halflength, $stream2->tell(), 'Tell halfway through Stream from Resource.');
        $this->assertSame(29, $stream3->tell(), 'Tell at end of Stream from Resource.');
    }
}
