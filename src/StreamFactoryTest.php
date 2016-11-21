<?php

namespace Http\FactoryTest;

use Interop\Http\Factory\StreamFactoryInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\StreamInterface;

class StreamFactoryTest extends TestCase
{
    /** @var  StreamFactoryInterface */
    private $factory;

    public function setUp()
    {
        $factoryClass = STREAM_FACTORY;
        $this->factory = new $factoryClass();
    }

    private function assertStream($stream, $content)
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
        $filename = tempnam(sys_get_temp_dir(), uniqid());
        file_put_contents($filename, $string);

        $stream = $this->factory->createStreamFromFile($filename);

        $this->assertStream($stream, $string);
    }

    public function testCreateStreamFromResource()
    {
        $string = 'would you like some crumpets?';

        $resource = tmpfile();
        fwrite($resource, $string);

        $stream = $this->factory->createStreamFromResource($resource);

        $this->assertStream($stream, $string);
    }
}
