<?php

namespace Differ\Differ;

use function Differ\Formatters\getFormatter;
use function Functional\sort;

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
                    'type' => 'immutable',
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

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    return getFormatter($pathToFile1, $pathToFile2, $format);
}
