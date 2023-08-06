<?php

namespace Differ\Differ;

use function Differ\Formatters\getFormatter;
use function Functional\sort;
use function Differ\Parsers\getParseCode;

function getArrayComparisonTree(mixed $array1, mixed $array2): mixed
{
    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));

    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    return array_map(
        function ($key) use ($array1, $array2) {
            if (
                array_key_exists($key, $array1) && array_key_exists($key, $array2)
                && is_array($array1[$key]) && is_array($array2[$key])
            ) {
                $nestedComparison = getArrayComparisonTree($array1[$key], $array2[$key]);

                return [
                    'key' => $key,
                    'type' => 'nested',
                    'value1' => $nestedComparison,
                    'value2' => $nestedComparison,
                ];
            } elseif (!array_key_exists($key, $array2)) {
                return [
                    'key' => $key,
                    'type' => 'deleted',
                    'value1' => $array1[$key],
                    'value2' => null,
                ];
            } elseif (!array_key_exists($key, $array1)) {
                return  [
                    'key' => $key,
                    'type' => 'added',
                    'value1' => null,
                    'value2' => $array2[$key],
                ];
            } elseif ($array1[$key] !== $array2[$key]) {
                return  [
                    'key' => $key,
                    'type' => 'updated',
                    'value1' => $array1[$key],
                    'value2' => $array2[$key],
                ];
            } else {
                return [
                    'key' => $key,
                    'type' => 'unchanged',
                    'value1' => $array1[$key],
                    'value2' => $array2[$key],
                ];
            }
        },
        $sortedKeys
    );
}

function getRealPath(string $pathToFile): string
{
    $fullPath = realpath($pathToFile);
    if ($fullPath === false) {
        throw new \Exception("File does not exists");
    }
    return $fullPath;
}

function getExtension(string $pathToFile): string
{
    $fullPath = getRealPath($pathToFile);
    return pathinfo($fullPath, PATHINFO_EXTENSION);
}

function getDataFile(string $pathToFile): mixed
{
    $fullPath = getRealPath($pathToFile);
    return file_get_contents($fullPath);
}

//функция форматирует значения входных массивов до сравнения, поэтому не относится к форматтерам:
//(Форматтеры форматируют массивы на выходе)
function getNoramalizeValue(mixed $dataArray): mixed
{
    return array_map(function ($value) {
        if ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_array($value)) {
            return getNoramalizeValue($value); // Рекурсивный вызов для обработки вложенных массивов
        }
        return $value;
    }, $dataArray);
}

function getData(string $pathToFile): mixed
{
    $extension = getExtension($pathToFile);
    $dataFile = getDataFile($pathToFile);
    return getNoramalizeValue(getParseCode($dataFile, $extension));
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data1 = getData($pathToFile1);
    $data2 = getData($pathToFile2);
    $diff = getArrayComparisonTree($data1, $data2);
    return getFormatter($diff, $format);
}
