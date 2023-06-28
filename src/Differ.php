<?php

namespace Differ\Differ;

function getDataArray(string $data): mixed
{
    $dataDecode = json_decode($data, true);

    $dataArray = array_map(function ($value) {
        if ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (is_null($value)) {
            return 'null';
        }
        return $value;
    }, $dataDecode);

    return $dataArray;
}

function getFullPath(string $path): string
{
    if (str_contains($path, '/')) {
        // Если файл в не в корне проекта, то это абсолютный путь
        return $path;
    } else {
        // Иначе (в корне проекта) строим полный путь относительно текущей директории
        $full_path = __DIR__ . '/../' . $path;
        return $full_path;
    }
}

function genDiff(string $pathToFile1, string $pathToFile2): string
{

    $data1 = file_get_contents(getFullPath($pathToFile1));
    $data2 = file_get_contents(getFullPath($pathToFile2));


    $dataArray1 = getDataArray($data1);
    $dataArray2 = getDataArray($data2);

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
