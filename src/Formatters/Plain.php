<?php

namespace Differ\Differ\Plain;

use const Differ\Differ\Constants\TYPES;

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
    $relevantNodes = array_filter($tree, fn($node) => $node['type'] !== TYPES['UNMODIFIED']);
    $result = array_map(
        function ($node) use ($lane): string {
            $type = $node['type'] ?? null;
            switch ($type) {
                case TYPES['PARENT']:
                    $parent = $node['key'];
                    $fullPropertyName = $lane . $parent . ".";
                    return makePlain($node['children'], $fullPropertyName);
                case TYPES['ADDED']:
                    $fullPropertyName = $lane . $node['key'];
                    $strValue = toString($node['value']);
                    return "Property '$fullPropertyName' was added with value: $strValue";
                case TYPES['MODIFIED']:
                    $fullPropertyName = $lane . $node['key'];
                    $strOldValue = toString($node['before']);
                    $strNewValue = toString($node['after']);
                    return "Property '$fullPropertyName' was updated. From $strOldValue to $strNewValue";
                case TYPES['DELETED']:
                    $fullPropertyName = $lane . $node['key'];
                    return "Property '$fullPropertyName' was removed";
                default:
                    throw new \Error("undefined property {$node['key']}");
            }
        },
        $relevantNodes
    );
    return implode(PHP_EOL, $result);
}
