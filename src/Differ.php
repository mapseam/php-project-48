<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\selectFormatter;

function getFileData(string $fileName): string
{
    if (!file_exists($fileName)) {
        throw new \Exception("File " . $fileName . " - not found");
    }

    $data = file_get_contents($fileName);

    if ($data === false) {
        throw new \Exception("Can't read file " . $fileName);
    }

    return $data;
}

function buildInternalStruct(array $oldData, array $newData): array
{
    $oldKeys = array_keys($oldData);
    $newKeys = array_keys($newData);

    $commonKeys = array_unique(array_merge($oldKeys, $newKeys));
    $sortedKeys = sort($commonKeys, fn ($left, $right) => strcmp($left, $right));

    $intStruct = array_map(function ($key) use ($oldData, $newData) {
        $oldValue = $oldData[$key] ?? null;
        $newValue = $newData[$key] ?? null;

        if (is_array($oldValue) && is_array($newValue)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => buildInternalStruct($oldValue, $newValue)
            ];
        }

        if (!array_key_exists($key, $newData)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'oldValue' => $oldValue
            ];
        }

        if (!array_key_exists($key, $oldData)) {
            return [
                'key' => $key,
                'status' => 'added',
                'newValue' => $newValue
            ];
        }

        if ($oldValue !== $newValue) {
            return [
                'key' => $key,
                'status' => 'changed',
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ];
        }

        return [
            'key' => $key,
            'status' => 'unchanged',
            'oldValue' => $oldValue
        ];
    }, $sortedKeys);

    return $intStruct;
}

function genDiff(string $oldFileName, string $newFileName, string $formatType = 'stylish'): string
{
    $oldFileExt = pathinfo($oldFileName, PATHINFO_EXTENSION);
    $newFileExt = pathinfo($newFileName, PATHINFO_EXTENSION);

    $oldFileData = getFileData($oldFileName);
    $newFileData = getFileData($newFileName);

    $parsedOldData = parse($oldFileData, $oldFileExt);
    $parsedNewData = parse($newFileData, $newFileExt);

    $intStruct = buildInternalStruct($parsedOldData, $parsedNewData);

    return selectFormatter($intStruct, $formatType);
}
