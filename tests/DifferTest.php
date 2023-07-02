<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function testGetFullPath(): void
    {

        $path1 = 'src/file.json';
        $this->assertEquals('src/file.json', Differ\getFullPath($path1));

        $path2 = 'file.json';
        $this->assertEquals('/home/dr4gonet/projects/php-project-48/src/../file.json', Differ\getFullPath($path2));
    }

    public function testGetDataArray(): void
    {

        $data1 = '{
            "host": "hexlet.io",
            "timeout": 50,
            "proxy": "123.234.53.22",
            "follow": false,
            "bool": true,
            "null": null,
            "test": "hello"
          }';

        $result1 = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => 'false',
            'bool' => 'true',
            'null' => 'null',
            'test' => 'hello',
        ];

        $this->assertEquals($result1, Differ\getDataArray($data1));
    }

    public function testGenDiff(): void
    {
        $pathToFile1 = 'tests/fixtures/file1.json';
        $pathToFile2 = 'tests/fixtures/file2.json';
        $result1 = '{
-follow: false
 host: hexlet.io
-proxy: 123.234.53.22
-timeout: 50
+timeout: 20
+verbose: true
}
';
        $this->assertEquals($result1, Differ\genDiff($pathToFile1, $pathToFile2));
    }
}
