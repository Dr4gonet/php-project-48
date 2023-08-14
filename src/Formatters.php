<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getFormat as getFormatStylish;
use function Differ\Formatters\Plain\getFormat as getFormatPlain;
use function Differ\Formatters\Json\getFormat as getFormatJson;

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
