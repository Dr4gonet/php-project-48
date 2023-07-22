<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStringsTree;
use function Differ\Formatters\Plain\getPropertyChange;

function getArrayComparisonTree(mixed $array1, mixed $array2): mixed
{
    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));
    sort($keys, SORT_REGULAR);

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
        $keys
    );

    return $result;
}


function getFormatter(mixed $dataArray1, mixed $dataArray2, string $format): mixed
{
    $result = '';
    $diffArray = getArrayComparisonTree($dataArray1, $dataArray2);
    if ($format === 'stylish') {
        $result = getStringsTree($diffArray, $replacer = ' ', $spaceCount = 4);
    }
    if ($format === 'plain') {
        $result = getPropertyChange($diffArray);
    }
    return $result;
}
