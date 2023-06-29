<?php

namespace Differ\Phpunit\Tests\DifferTest;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function testGetFullPath(): void
    {

       // $path1 = 'src/file.json';

        $this->assertEquals('src/file.json', Differ\getFullPath('src/file.json'));
    }
}
