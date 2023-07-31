<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Differ\Differ;

final class DifferTest extends TestCase
{
    public static function additionProvider(): mixed
    {
        return [
            [
                'tests/fixtures/file1.json',
                'tests/fixtures/file2.json',
                'stylish',
                'tests/fixtures/formatStylish.txt'
            ],
            [
                'tests/fixtures/file1.yml',
                'tests/fixtures/file2.yml',
                'stylish',
                'tests/fixtures/formatStylish.txt'
            ],
            [
                'tests/fixtures/file1.json',
                'tests/fixtures/file2.json',
                'plain',
                'tests/fixtures/formatPlain.txt'
            ],
            [
                'tests/fixtures/file1.yml',
                'tests/fixtures/file2.yml',
                'plain',
                'tests/fixtures/formatPlain.txt'
            ],
            [
                'tests/fixtures/file1.json',
                'tests/fixtures/file2.json',
                'json',
                'tests/fixtures/formatJson.txt'
            ],
            [
                'tests/fixtures/file1.yml',
                'tests/fixtures/file2.yml',
                'json',
                'tests/fixtures/formatJson.txt'
            ],
        ];
    }

    #[DataProvider('additionProvider')]
    public function testGenDiff(string $pathToFile1, string $pathToFile2, string $format, string $pathToExpected): void
    {
        $this->assertStringEqualsFile($pathToExpected, Differ\genDiff($pathToFile1, $pathToFile2, $format));
    }
}
