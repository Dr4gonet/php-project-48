<?php

namespace Differ\Formatters\Plain;

function getPropertyChange(mixed $diffArray): string
{
    $result = array_map(function ($value) {
        if ($value['type'] === 'immutable') {
            return null;
        }
        if ($value['type'] === 'deleted') {
            return "Property '" . $value['key'] . "' was removed";
        }
        if ($value['type'] === 'added') {
            return "Property '" . $value['key'] . "' was added with value: " . $value['value'];
        }
        if ($value['type'] === 'updated') {
            return "Property '" . $value['key'] . "' was updated. From " . $value['value1'] . ' to ' . $value['value2'];
        }
    }, $diffArray);

    $result = array_filter($result); // Удаляем пустые значения из массива

    return  implode("\n", $result);
}
