<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
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

        $pathToFile3 = 'tests/fixtures/file1.yml';
        $pathToFile4 = 'tests/fixtures/file2.yml';
        $result2 = '{
-follow: false
 host: hexlet.io
-proxy: 123.234.53.22
-timeout: 50
+timeout: 20
+verbose: true
}
';
        $this->assertEquals($result2, Differ\genDiff($pathToFile3, $pathToFile4));
    }
}
