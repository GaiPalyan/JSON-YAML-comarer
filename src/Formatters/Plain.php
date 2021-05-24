<?php

namespace Differ\Differ\Plain;

/**
 * @param mixed $value
 * @return string
 */
function toString($value): string
{
    if (is_string($value)) {
        return "'{$value}'";
    }
    if (is_null($value)) {
        return 'null';
    }
    return is_object($value) ? '[complex value]' : trim(var_export($value, true), "'");
}

/**
 * @param array $tree
 * @param string $lane
 * @return string
 */
function makePlain(array $tree, string $lane = ''): string
{
    $relevantNodes = array_filter($tree, fn($node) => $node['type'] !== 'unchanged');
    $result = array_map(
        function ($node) use ($lane): string {
            $type = $node['type'] ?? null;
            switch ($type) {
                case 'parent':
                    $parent = $node['key'];
                    $fullPropertyName = $lane . $parent . ".";
                    return makePlain($node['children'], $fullPropertyName);
                case 'added':
                    $fullPropertyName = $lane . $node['key'];
                    $strValue = toString($node['value']);
                    return "Property '$fullPropertyName' was added with value: $strValue";
                case 'modified':
                    $fullPropertyName = $lane . $node['key'];
                    $strOldValue = toString($node['before']);
                    $strNewValue = toString($node['after']);
                    return "Property '$fullPropertyName' was updated. From $strOldValue to $strNewValue";
                case 'delete':
                    $fullPropertyName = $lane . $node['key'];
                    return "Property '$fullPropertyName' was removed";
                default:
                    throw new \Error("undefined property {$node['key']}");
            }
        },
        $relevantNodes
    );
    return implode("\n", $result);
}
