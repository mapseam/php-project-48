<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Gendiff\Helpers\getFixturePath;
use function Gendiff\Helpers\getFileData;

/**
 * @throws \Exception
 */
function parse(string $fileName): array
{
    $fileData = getFileData(getFixturePath($fileName));
    $fileNameExt = pathinfo($fileName, PATHINFO_EXTENSION);

    return match ($fileNameExt) {
        'yaml', 'yml' => Yaml::parse($fileData),
        'json' => json_decode($fileData, true),
        default => throw new \Exception("Unsupported file format: $fileNameExt"),
    };
}
