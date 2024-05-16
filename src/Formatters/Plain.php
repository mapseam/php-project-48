<?php

namespace Differ\Formatters\Plain;

function render(array $intStruct, array $valuePath = []): string
{
    $lines = array_map(function ($node) use ($valuePath) {
        $value1 = stringify($node['value1'] ?? null);
        $value2 = stringify($node['value2'] ?? null);
        $fullValuePath = array_merge($valuePath, [$node['key']]);
        $path = implode('.', $fullValuePath);

        $result = "";

        $status = $node['status'];
        switch ($status) {
            case 'nested':
                $result = render($node['children'], $fullValuePath);
                break;
            case 'unchanged':
                break;
            case 'added':
                $result = "Property '$path' was added with value: $value2";
                break;
            case 'deleted':
                $result = "Property '$path' was removed";
                break;
            case 'changed':
                $result = "Property '$path' was updated. From $value1 to $value2";
                break;
            default:
                throw new \Exception("Unknown node status: '$status'");
        }

        return $result;
    }, $intStruct);

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
