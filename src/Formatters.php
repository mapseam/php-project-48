<?php

namespace Differ\Formatters;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;
use Differ\Formatters\Json;

function format(array $intStruct, string $formatType): string
{
    return match ($formatType) {
        'stylish' => Stylish\render($intStruct),
        'plain' => Plain\render($intStruct),
        'json' => Json\render($intStruct),
        default => throw new \Exception("Unknown format: '$formatType'"),
    };
}
