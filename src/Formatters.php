<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getFormat as getFormatStylish;
use function Differ\Formatters\Plain\getFormat as getFormatPlain;
use function Differ\Formatters\Json\getFormat as getFormatJson;
use function Differ\Parsers\getParseCode;
use function Differ\Differ\getExtension;
use function Differ\Differ\getDataFile;
use function Differ\Differ\getArrayComparisonTree;

function getFormattedArray(mixed $dataArray): mixed
{
    return array_map(function ($value) {
        if ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_array($value)) {
            return getFormattedArray($value); // Рекурсивный вызов для обработки вложенных массивов
        }
        return $value;
    }, $dataArray);
}

function getFormatter(string $pathToFile1, string $pathToFile2, string $format): string
{
    $extension1 = getExtension($pathToFile1);
    $extension2 = getExtension($pathToFile2);

    $dataFile1 = getDataFile($pathToFile1);
    $dataFile2 = getDataFile($pathToFile2);

    $data1 = getFormattedArray(getParseCode($dataFile1, $extension1));
    $data2 = getFormattedArray(getParseCode($dataFile2, $extension2));

    $diffArray = getArrayComparisonTree($data1, $data2);


    switch ($format) {
        case 'stylish':
            return getFormatStylish($diffArray, $replacer = ' ', $spaceCount = 4);
        case 'plain':
            return getFormatPlain($diffArray);
        case 'json':
            return getFormatJson($diffArray);
        default:
            throw new \Exception('Unknown format ' . $format);
    }
}
