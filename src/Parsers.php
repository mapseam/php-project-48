<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @throws \Exception
 */
function parse(string $fileData, string $fileExt): array
{
    return match ($fileExt) {
        'yaml', 'yml' => Yaml::parse($fileData),
        'json' => json_decode($fileData, true),
        default => throw new \Exception("Unsupported file format: $fileExt"),
    };
}
