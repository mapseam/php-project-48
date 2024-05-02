<?php

namespace Gendiff\Helpers;

function getFixturePath($fileName)
{
    $fileNameParts = [__DIR__, '../tests/fixtures', $fileName];
    return implode('/', $fileNameParts);
}

function getFileData(string $fileName): string
{
    $data = file_get_contents($fileName);

    if ($data === false) {
        throw new \Exception("Can't read file " . $fileName);
    }

    return $data;
}
