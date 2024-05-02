<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Differ\Helpers\getFixturePath;
use function Differ\Helpers\getFileData;

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
