<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\format;

function getFileData(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new \Exception("File " . $filePath . " - not found");
    }

    $fileData = file_get_contents($filePath);

    if ($fileData === false) {
        throw new \Exception("Can't read file " . $filePath);
    }

    return $fileData;
}

function getDiffData(array $sortedKeys, array $data1, array $data2): array
{
    return array_map(function ($key) use ($data1, $data2) {
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        return match (true) {
            (is_array($value1) && is_array($value2)) =>
            [
                'key' => $key,
                'status' => 'nested',
                'children' => buildDiff($value1, $value2)
            ],
            (!array_key_exists($key, $data2)) =>
            [
                'key' => $key,
                'status' => 'deleted',
                'value1' => $value1
            ],
            (!array_key_exists($key, $data1)) =>
            [
                'key' => $key,
                'status' => 'added',
                'value2' => $value2
            ],
            ($value1 !== $value2) =>
            [
                'key' => $key,
                'status' => 'changed',
                'value1' => $value1,
                'value2' => $value2
            ],
            default =>
            [
                'key' => $key,
                'status' => 'unchanged',
                'value1' => $value1
            ],
        };
    }, $sortedKeys);
}

function buildDiff(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);

    $commonKeys = array_unique(array_merge($keys1, $keys2));
    $sortedKeys = sort($commonKeys, fn ($left, $right) => strcmp($left, $right));

    $diffData = getDiffData($sortedKeys, $data1, $data2);

    return $diffData;
}

function genDiff(string $firstFilePath, string $secondFilePath, string $formatType = 'stylish'): string
{
    $firstFileData = getFileData($firstFilePath);
    $secondFileData = getFileData($secondFilePath);

    $firstFileExt = pathinfo($firstFilePath, PATHINFO_EXTENSION);
    $secondFileExt = pathinfo($secondFilePath, PATHINFO_EXTENSION);

    $firstParsedData = parse($firstFileData, $firstFileExt);
    $secondParsedData = parse($secondFileData, $secondFileExt);

    $diff = buildDiff($firstParsedData, $secondParsedData);

    return format($diff, $formatType);
}
