<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function genDiff(string $pathToFile1, string $pathToFile2): string
{

    $dataArray1 = parse($pathToFile1);
    $dataArray2 = parse($pathToFile2);

    $keys = array_unique(array_merge(array_keys($dataArray1), array_keys($dataArray2)));

    sort($keys, SORT_REGULAR);

    $result = array_reduce($keys, function ($acc, $key) use ($dataArray1, $dataArray2) {
        if (!array_key_exists($key, $dataArray1)) {
            $acc[] = '+' . $key . ': ' . $dataArray2[$key];
        } elseif (!array_key_exists($key, $dataArray2)) {
            $acc[] = '-' . $key . ': ' . $dataArray1[$key];
        } elseif ($dataArray1[$key] !== $dataArray2[$key]) {
            $acc[] = '-' . $key . ': ' . $dataArray1[$key];
            $acc[] = '+' . $key . ': ' . $dataArray2[$key];
        } else {
            $acc[] = ' ' . $key . ': ' . $dataArray1[$key];
        };
        return $acc;
    }, []);

    return '{' . "\n" . implode("\n", $result) . "\n" . '}' . "\n";
}
