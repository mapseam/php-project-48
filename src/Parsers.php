<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @throws \Exception
 */
function parse(string $fileData, string $fileFormat): array
{
    return match ($fileFormat) {
        'yaml', 'yml' => Yaml::parse($fileData),
        'json' => json_decode($fileData, true),
        '' => throw new \Exception("The file format is empty"),
        default => throw new \Exception("Unsupported file format: $fileFormat"),
    };
}
