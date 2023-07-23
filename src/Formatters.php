<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStringsTree;
use function Differ\Formatters\Plain\getPropertyChange;
use function Differ\Formatters\Json\getJsonFormat;
use function Differ\Differ\getArrayComparisonTree;

function getFormatter(mixed $dataArray1, mixed $dataArray2, string $format): string
{
    $diffArray = getArrayComparisonTree($dataArray1, $dataArray2);

    switch ($format) {
        case 'stylish':
            return getStringsTree($diffArray, $replacer = ' ', $spaceCount = 4);
        case 'plain':
            return getPropertyChange($diffArray);
        case 'json':
            return getJsonFormat($diffArray);
        default:
            throw new \Exception('Unknown format ' . $format);
    }
}
