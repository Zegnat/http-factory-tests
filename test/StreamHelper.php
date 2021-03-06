<?php

namespace Interop\Http\Factory;

trait StreamHelper
{
    protected static $tempFiles = [];

    protected function createTemporaryFile()
    {
        return static::$tempFiles[] = tempnam(sys_get_temp_dir(), 'http_factory_tests_');
    }

    protected function createTemporaryResource($content = null)
    {
        $file = $this->createTemporaryFile();
        $resource = fopen($file, 'r+');

        if ($content) {
            fwrite($resource, $content);
            rewind($resource);
        }

        return $resource;
    }

    public static function tearDownAfterClass()
    {
        foreach (static::$tempFiles as $tempFile) {
            @unlink($tempFile);
        }
    }
}
