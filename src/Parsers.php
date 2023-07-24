<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Differ\Differ\getFormattedArray;
use function Differ\Differ\getExtension;
use function Differ\Differ\getData;

function getParseCode(string $pathToFile): mixed
{
    $data = getData($pathToFile);
    $extension = getExtension($pathToFile);

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
