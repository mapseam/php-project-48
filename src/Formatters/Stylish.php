<?php

namespace Gendiff\Formatters\Stylish;

function render(array $tree, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);

    $lines = array_map(function ($node) use ($indent, $depth) {
        $key = $node['key'];
        $oldValue = stringify(($node['oldValue'] ?? null), $depth);
        $newValue = stringify(($node['newValue'] ?? null), $depth);

        $status = $node['status'];
        switch ($status) {
            case 'nested':
                $nestedNode = render($node['children'], $depth + 1);
                return "$indent    $key: $nestedNode";
            case 'unchanged':
                return "$indent    $key: $oldValue";
            case 'added':
                return "$indent  + $key: $newValue";
            case 'deleted':
                return "$indent  - $key: $oldValue";
            case 'changed':
                return "$indent  - $key: $oldValue\n$indent  + $key: $newValue";
            default:
                throw new \Exception("Unknown node status: '$status'");
        }
    }, $tree);

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
