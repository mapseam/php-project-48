<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\format;

function getFileData(string $fileName): string
{
    if (!file_exists($fileName)) {
        throw new \Exception("File " . $fileName . " - not found");
    }

    $fileData = file_get_contents($fileName);

    if ($fileData === false) {
        throw new \Exception("Can't read file " . $fileName);
    }

    return $fileData;
}

function makeMap(array $sortedKeys, array $data1, array $data2): array
{
    return array_map(function ($key) use ($data1, $data2) {
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if (is_array($value1) && is_array($value2)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => buildInternalStruct($value1, $value2)
            ];
        } elseif (!array_key_exists($key, $data2)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'value1' => $value1
            ];
        } elseif (!array_key_exists($key, $data1)) {
            return [
                'key' => $key,
                'status' => 'added',
                'value2' => $value2
            ];
        } elseif ($value1 !== $value2) {
            return [
                'key' => $key,
                'status' => 'changed',
                'value1' => $value1,
                'value2' => $value2
            ];
        } else {
            return [
                'key' => $key,
                'status' => 'unchanged',
                'value1' => $value1
            ];
        }
    }, $sortedKeys);
}

function buildInternalStruct(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);

    $commonKeys = array_unique(array_merge($keys1, $keys2));
    $sortedKeys = sort($commonKeys, fn ($left, $right) => strcmp($left, $right));

    $intStruct = makeMap($sortedKeys, $data1, $data2);

    return $intStruct;
}

function genDiff(string $firstFileName, string $secondFileName, string $formatType = 'stylish'): string
{
    $firstFileData = getFileData($firstFileName);
    $secondFileData = getFileData($secondFileName);

    $firstFileExt = pathinfo($firstFileName, PATHINFO_EXTENSION);
    $secondFileExt = pathinfo($secondFileName, PATHINFO_EXTENSION);

    $firstParsedData = parse($firstFileData, $firstFileExt);
    $secondParsedData = parse($secondFileData, $secondFileExt);

    $intStruct = buildInternalStruct($firstParsedData, $secondParsedData);

    return format($intStruct, $formatType);
}
