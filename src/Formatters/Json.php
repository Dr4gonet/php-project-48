<?php

namespace Differ\Formatters\Json;

function getJsonFormat(mixed $diffArray): string
{
    return json_encode($diffArray);
}
