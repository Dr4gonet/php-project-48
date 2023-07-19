<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStringsTree;
use function Differ\Formatters\Plain\getPropertyChange;

function getArrayComparisonTree(mixed $array1, mixed $array2): mixed
{
    $result = [];

    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));

    sort($keys, SORT_REGULAR);

    foreach ($keys as $key) {
        if (
            array_key_exists($key, $array1) && array_key_exists($key, $array2)
            && is_array($array1[$key]) && is_array($array2[$key])
        ) {
            $nestedComparison = getArrayComparisonTree($array1[$key], $array2[$key]);

            $result[] = [
                'key' => $key,
                'type' => 'immutable',
                'value' => $nestedComparison,
            ];
        } elseif (!array_key_exists($key, $array2)) {
            $result[] = [
                'key' => $key,
                'type' => 'deleted',
                'value' => $array1[$key],
            ];
        } elseif (!array_key_exists($key, $array1)) {
            $result[] = [
                'key' => $key,
                'type' => 'added',
                'value' => $array2[$key],
            ];
        } elseif ($array1[$key] !== $array2[$key]) {
            $result[] = [
                'key' => $key,
                'type' => 'deleted',
                'value' => $array1[$key],
            ];
            $result[] = [
                'key' => $key,
                'type' => 'added',
                'value' => $array2[$key],
            ];
        } else {
            $result[] = [
                'key' => $key,
                'type' => 'immutable',
                'value' => $array1[$key],
            ];
        }
    }

    return $result;
}

function getArrayComparisonTreeForFormatPlain(mixed $array1, mixed $array2): mixed
{
    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));
    sort($keys, SORT_REGULAR);

    $result = array_map(
        function ($key) use ($array1, $array2) {
            if (
                array_key_exists($key, $array1) && array_key_exists($key, $array2)
                && is_array($array1[$key]) && is_array($array2[$key])
            ) {
                $nestedComparison = getArrayComparisonTreeForFormatPlain($array1[$key], $array2[$key]);

                return [
                    'key' => $key,
                    'type' => 'immutable',
                    'value' => $nestedComparison,
                ];
            } elseif (!array_key_exists($key, $array2)) {
                return [
                    'key' => $key,
                    'type' => 'deleted',
                    'value' => $array1[$key],
                ];
            } elseif (!array_key_exists($key, $array1)) {
                return  [
                    'key' => $key,
                    'type' => 'added',
                    'value' => $array2[$key],
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
                    'value' => $array1[$key],
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
    if ($format === 'stylish') {
        $diffArray = getArrayComparisonTree($dataArray1, $dataArray2);
        $result = getStringsTree($diffArray, $replacer = ' ', $spaceCount = 4);
    }
    if ($format === 'plain') {
        $diffArray = getArrayComparisonTreeForFormatPlain($dataArray1, $dataArray2);
        $result = getPropertyChange($diffArray);
    }
    return $result;
}
