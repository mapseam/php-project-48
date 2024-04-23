<?php

namespace Gendiff\Differ;

use function Gendiff\Parsers\convert;

function getRealPath($path)
{
    $parts = [__DIR__, '../tests/fixtures', $path];
    return realpath(implode('/', $parts));
}

function getFileContent($fileName)
{
    $fileNameParts = pathinfo($fileName);

    if (file_exists($fileName)) {
        $fileContent = file_get_contents($fileName);
        $fileData = convert($fileContent, $fileNameParts['extension']);
    } else {
        throw new \Exception("Unable to open file: '{$fileName}'!");
    }

    foreach ($fileData as $key => $value) {
        if (is_bool($value)) {
            $fileData[$key] = ($value) ? 'true' : 'false';
        }
    }

    return $fileData;
}

function toString($arr)
{
    $str = "{\n";
    foreach ($arr as $value) {
        $str .= $value . "\n";
    }
    $str .= "}\n";

    return $str;
}

function genDiff($filePath1, $filePath2, $format = "stylish")
{
    $orgFile = getRealPath($filePath1);
    $modFile = getRealPath($filePath2);

    $orgData = getFileContent($orgFile);
    $modData = getFileContent($modFile);

    $orgPairs = [];
    foreach ($orgData as $orgKey => $orgValue) {
        if (array_key_exists($orgKey, $modData)) {
            foreach ($modData as $modKey => $modValue) {
                if ($orgKey === $modKey && $orgValue == $modValue) {
                    $orgPairs[] = "    {$orgKey}: {$orgValue}";
                } elseif ($orgKey === $modKey && $orgValue != $modValue) {
                    $orgPairs[] = "  - {$orgKey}: {$orgValue}";
                }
            }
        } else {
            $orgPairs[] = "  - {$orgKey}: {$orgValue}";
        }
    }
    sort($orgPairs);

    $modPairs = [];
    foreach ($modData as $modKey => $modValue) {
        if (!array_key_exists($modKey, $orgData)) {
            $modPairs[] = "  + {$modKey}: {$modValue}";
        } else {
            foreach ($orgData as $orgKey => $orgValue) {
                if ($orgKey == $modKey && $orgValue != $modValue) {
                    $modPairs[] = "  + {$orgKey}: {$modValue}";
                }
            }
        }
    }
    sort($modPairs);

    $pairs = array_merge($orgPairs, $modPairs);

    return toString($pairs);
}
