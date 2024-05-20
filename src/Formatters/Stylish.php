<?php

namespace Differ\Formatters\Stylish;

const COMMON_INDENT = '    ';
const LEVEL_INDENT = '  ';

function render(array $diff, int $depth = 0): string
{
    $indent = str_repeat(COMMON_INDENT, $depth);

    $lines = array_map(function ($node) use ($indent, $depth) {
        $commonIndent = $indent . \Differ\Formatters\Stylish\COMMON_INDENT;
        $levelIndent = $indent . \Differ\Formatters\Stylish\LEVEL_INDENT;
        $key = $node['key'];
        $value1 = stringify(($node['value1'] ?? null), $depth);
        $value2 = stringify(($node['value2'] ?? null), $depth);

        $status = $node['status'];
        return match ($status) {
            'nested' => $commonIndent . "$key: " . render($node['children'], $depth + 1),
            'unchanged' => $commonIndent . "$key: $value1",
            'added' => $levelIndent . "+ $key: $value2",
            'deleted' => $levelIndent . "- $key: $value1",
            'changed' => $levelIndent . "- $key: $value1\n" . $levelIndent . "+ $key: $value2",
            default => throw new \Exception("Unknown node status: '$status'"),
        };
    }, $diff);

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

    if (is_array($value)) {
        $indent = str_repeat(COMMON_INDENT, $depth + 1);
        $keys = array_keys($value);

        $lines = array_map(function ($key) use ($value, $indent, $depth) {
            $commonIndent = $indent . \Differ\Formatters\Stylish\COMMON_INDENT;
            $result = stringify($value[$key], $depth + 1);
            return $commonIndent . "$key: $result";
        }, $keys);

        $string = implode("\n", $lines);

        return "{\n$string\n$indent}";
    }

    return $value;
}
