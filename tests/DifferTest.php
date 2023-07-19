<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $pathToFile1 = 'tests/fixtures/file3.json';
        $pathToFile2 = 'tests/fixtures/file4.json';
        $result1 = '{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
';
        $this->assertEquals($result1, Differ\genDiff($pathToFile1, $pathToFile2));

        $pathToFile3 = 'tests/fixtures/file3.yaml';
        $pathToFile4 = 'tests/fixtures/file4.yaml';
        $result2 = '{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
';
        $this->assertEquals($result2, Differ\genDiff($pathToFile3, $pathToFile4));

        $pathToFile5 = 'tests/fixtures/file1.json';
        $pathToFile6 = 'tests/fixtures/file2.json';
        $result3 = file_get_contents('tests/fixtures/formatStylish.txt');

        $this->assertEquals($result3, Differ\genDiff($pathToFile5, $pathToFile6));

        $pathToFile7 = 'tests/fixtures/file1.yml';
        $pathToFile8 = 'tests/fixtures/file2.yml';

        $this->assertEquals($result3, Differ\genDiff($pathToFile7, $pathToFile8, 'stylish'));

        $result4 = file_get_contents('tests/fixtures/formatPlain.txt');

        $this->assertEquals($result4, Differ\genDiff($pathToFile5, $pathToFile6, 'plain'));

        $this->assertEquals($result4, Differ\genDiff($pathToFile7, $pathToFile8, 'plain'));
    }
}
