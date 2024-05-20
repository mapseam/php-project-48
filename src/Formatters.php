<?php

namespace Differ\Formatters;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;
use Differ\Formatters\Json;

function format(array $diff, string $formatType): string
{
    return match ($formatType) {
        'stylish' => Stylish\render($diff),
        'plain' => Plain\render($diff),
        'json' => Json\render($diff),
        default => throw new \Exception("Unknown format: '$formatType'"),
    };
}
