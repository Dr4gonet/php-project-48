<?php

namespace Differ\Differ;

use function Differ\Parsers\getParseCode;
use function Differ\Formatters\getFormatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{

    $dataArray1 = getParseCode($pathToFile1);
    $dataArray2 = getParseCode($pathToFile2);

    $result = getFormatter($dataArray1, $dataArray2, $format);
    return $result . "\n";
}
