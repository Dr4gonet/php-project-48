<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Differ\Differ;

final class DifferTest extends TestCase
{
    private function getFixtureFullPath(string $fixtureName): string
    {
        return 'tests/fixtures/' . $fixtureName;
    }

    public static function additionProvider(): mixed
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'formatStylish.txt'],
            ['file1.yml', 'file2.yml', 'stylish', 'formatStylish.txt'],
            ['file1.json', 'file2.json', 'plain', 'formatPlain.txt'],
            ['file1.yml', 'file2.yml', 'plain', 'formatPlain.txt'],
            ['file1.json', 'file2.json', 'json', 'formatJson.txt'],
            ['file1.yml', 'file2.yml', 'json', 'formatJson.txt'],
        ];
    }

    #[DataProvider('additionProvider')]
    public function testGenDiff(string $file1, string $file2, string $format, string $expected): void
    {
        $fixture1 = $this->getFixtureFullPath($file1);
        $fixture2 = $this->getFixtureFullPath($file2);
        $PathToExpected = $this->getFixtureFullPath($expected);

        $this->assertStringEqualsFile($PathToExpected, Differ\genDiff($fixture1, $fixture2, $format));
    }
}
