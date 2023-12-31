<?php

namespace Differ\Formatters\Stylish;

function toString(string $value): string
{
    return trim(var_export($value, true), "'");
}

function getFormat(mixed $value, string $replacer = ' ', int $spaceCount = 4): string
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
        $indentForImmutableType = str_repeat($replacer, $indentLength);
        $indentForMutableType = str_repeat($replacer, $indentLength - $shiftToLeft);
        $bracketIndent = str_repeat($replacer, $indentLength - $spaceCount);

        $strings = array_map(
            function ($item, $key) use ($indentForImmutableType, $indentForMutableType, $iter, $depth) {
                if (!is_array($item)) {
                    return $indentForImmutableType . $key . ': ' . $iter($item, $depth + 1);
                }
                if (!array_key_exists('type', $item)) {
                    return $indentForImmutableType . $key . ': ' . $iter($item, $depth + 1);
                }
                if ($item['type'] === 'added') {
                    return $indentForMutableType . '+ ' . $item['key'] . ': ' . $iter($item['value2'], $depth + 1);
                }
                if ($item['type'] === 'deleted') {
                    return $indentForMutableType . '- ' . $item['key'] . ': ' . $iter($item['value1'], $depth + 1);
                }
                if ($item['type'] === 'updated') {
                    return  $indentForMutableType . '- ' . $item['key'] . ': ' . $iter($item['value1'], $depth + 1) .
                        "\n" .  $indentForMutableType . '+ ' . $item['key'] . ': ' . $iter($item['value2'], $depth + 1);
                }
                return $indentForImmutableType . $item['key'] . ': ' . $iter($item['value1'], $depth + 1);
            },
            $currentValue,
            array_keys($currentValue)
        );
        $result = ['{', ...$strings, $bracketIndent . '}'];

        return implode("\n", $result);
    };
    return $iter($value, 1);
}
