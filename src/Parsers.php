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

function parse(string $pathToFile): mixed
{
    $fullPath = getRealPath($pathToFile);
    $data = file_get_contents($fullPath);
    $extension = pathinfo($fullPath)['extension'];
    $dataArray = [];

    if ($extension === 'yaml' || $extension === 'yml') {
        $dataArray = Yaml::parse($data);
    }

    if ($extension === 'json') {
        $dataArray = json_decode($data, true);
    }

    $resultArray = array_map(function ($value) {
        if ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (is_null($value)) {
            return 'null';
        }
        return $value;
    }, $dataArray);
    return $resultArray;
}
