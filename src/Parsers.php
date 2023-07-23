<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getRealPath(string $pathToFile): string
{
    $fullPath = realpath($pathToFile);
    if ($fullPath === false) {
        throw new \Exception("File does not exists");
    }
    return $fullPath;
}

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

function getParseCode(string $pathToFile): mixed
{
    $fullPath = getRealPath($pathToFile);
    $data = file_get_contents($fullPath);
    $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    switch ($extension) {
        case 'json':
            return getFormattedArray(json_decode($data, true));
        case 'yml':
            return getFormattedArray(Yaml::parse($data));
        case 'yaml':
            return getFormattedArray(Yaml::parse($data));
        default:
            throw new \Exception('Unknown extension ' . $extension);
    }
}
