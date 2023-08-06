<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getFormat as getFormatStylish;
use function Differ\Formatters\Plain\getFormat as getFormatPlain;
use function Differ\Formatters\Json\getFormat as getFormatJson;

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

function getFormatter(mixed $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return getFormatStylish($diff);
        case 'plain':
            return getFormatPlain($diff);
        case 'json':
            return getFormatJson($diff);
        default:
            throw new \Exception('Unknown format ' . $format);
    }
}
