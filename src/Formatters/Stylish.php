<?php

namespace Differ\Formatters\Stylish;

function render(array $intStruct, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);

    $lines = array_map(function ($node) use ($indent, $depth) {
        $key = $node['key'];
        $value1 = stringify(($node['value1'] ?? null), $depth);
        $value2 = stringify(($node['value2'] ?? null), $depth);

        $result = "";

        $status = $node['status'];
        switch ($status) {
            case 'nested':
                $nestedNode = render($node['children'], $depth + 1);
                $result = "$indent    $key: $nestedNode";
                break;
            case 'unchanged':
                $result = "$indent    $key: $value1";
                break;
            case 'added':
                $result = "$indent  + $key: $value2";
                break;
            case 'deleted':
                $result = "$indent  - $key: $value1";
                break;
            case 'changed':
                $result = "$indent  - $key: $value1\n$indent  + $key: $value2";
                break;
            default:
                throw new \Exception("Unknown node status: '$status'");
        }

        return $result;
    }, $intStruct);

    $output = ["{", ...$lines, "$indent}"];

    return implode("\n", $output);
}

function stringify(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "$value";
    }

    if (is_array($value)) {
        $indent = str_repeat('    ', $depth + 1);
        $keys = array_keys($value);

        $lines = array_map(function ($key) use ($value, $indent, $depth) {
            $result = stringify($value[$key], $depth + 1);
            return "$indent    $key: $result";
        }, $keys);

        $string = implode("\n", $lines);

        return "{\n$string\n$indent}";
    }

    return $value;
}
