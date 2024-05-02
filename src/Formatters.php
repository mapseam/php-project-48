<?php

namespace Gendiff\Formatters;

use Gendiff\Formatters\Stylish;
use Gendiff\Formatters\Plain;
use Gendiff\Formatters\Json;

function selectFormatter(array $intStruct, string $formatType): string
{
    return match ($formatType) {
        'stylish' => Stylish\render($intStruct),
        'plain' => Plain\render($intStruct),
        'json' => Json\render($intStruct),
        default => throw new \Exception("Unknown format: '$formatType'"),
    };
}
