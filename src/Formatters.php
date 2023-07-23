<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStringsTree;
use function Differ\Formatters\Plain\getPropertyChange;
use function Differ\Formatters\Json\getJsonFormat;
use function Functional\sort;

function getArrayComparisonTree(mixed $array1, mixed $array2): mixed
{
    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));

    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(
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

    return $result;
}


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
