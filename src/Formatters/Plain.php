<?php

namespace Differ\Formatters\Plain;

function render(array $diff, array $valuePath = []): string
{
    $lines = array_map(function ($node) use ($valuePath) {
        $value1 = stringify($node['value1'] ?? null);
        $value2 = stringify($node['value2'] ?? null);
        $fullValuePath = array_merge($valuePath, [$node['key']]);
        $path = implode('.', $fullValuePath);

        $status = $node['status'];
        return match ($status) {
            'nested' => render($node['children'], $fullValuePath),
            'unchanged' => "",
            'added' => "Property '$path' was added with value: $value2",
            'deleted' => "Property '$path' was removed",
            'changed' => "Property '$path' was updated. From $value1 to $value2",
            default => throw new \Exception("Unknown node status: '$status'"),
        };
    }, $diff);

    $output = array_filter($lines);

    return implode("\n", $output);
}

function stringify(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    if (is_array($value)) {
        return "[complex value]";
    }

    return $value;
}
