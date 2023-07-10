<?php

namespace Differ\Differ;

use function Differ\Parsers\getParseCode;

function compareArrays(mixed $array1, mixed $array2): mixed
{
    $result = [];
    $keys = array_unique(array_merge(array_keys($array1), array_keys($array2)));

    sort($keys, SORT_REGULAR);

    foreach ($keys as $key) {
        if (
            array_key_exists($key, $array1) && array_key_exists($key, $array2)
            && is_array($array1[$key]) && is_array($array2[$key])
        ) {
            $nestedComparison = compareArrays($array1[$key], $array2[$key]);
            $result[' ' . $key] = $nestedComparison;
        } elseif (!array_key_exists($key, $array2)) {
            $result['- ' . $key] = $array1[$key];
        } elseif (!array_key_exists($key, $array1)) {
            $result['+ ' . $key] = $array2[$key];
        } elseif ($array1[$key] !== $array2[$key]) {
            $result['- ' . $key] = $array1[$key];
            $result['+ ' . $key] = $array2[$key];
        } else {
            $result[' ' . $key] = $array1[$key];
        }
    }

    return $result;
}

function toString(string $value): string
{
    return trim(var_export($value, true), "'");
}


function stringify(mixed $value, string $replacer = ' ', int $spaceCount = 4): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spaceCount) {

        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentLength = $spaceCount * $depth;
        $shiftToLeft = 2;
        $indent = str_repeat($replacer, $indentLength - $shiftToLeft);
        $bracketIndent = str_repeat($replacer, $indentLength - $spaceCount);

        $strings = array_map(
            fn ($key, $item) => $indent . $key . ': ' . $iter($item, $depth + 1),
            array_keys($currentValue),
            $currentValue
        );

        $result = ['{', ...$strings, $bracketIndent . '}'];

        return implode("\n", $result);
    };
    return $iter($value, 1);
}



function genDiff(string $pathToFile1, string $pathToFile2): string
{

    $dataArray1 = getParseCode($pathToFile1);
    $dataArray2 = getParseCode($pathToFile2);

    $diffArray = compareArrays($dataArray1, $dataArray2);

    $result = stringify($diffArray, $replacer = ' ', $spaceCount = 4);

    return $result;
}
