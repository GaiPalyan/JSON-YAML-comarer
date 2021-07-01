<?php

declare(strict_types=1);

namespace Differ\Differ\Stylish;

use const Differ\Differ\Constants\INDENT;
use const Differ\Differ\Constants\INDENTS_SIZE;
use const Differ\Differ\Constants\TYPES;

/**
 * @param array $tree
 * @param int $depth
 * @return string
 */
function makeStylish(array $tree, int $depth = 1): string
{
    $result = implode(PHP_EOL, array_map(
        function ($node) use ($depth): string {
            $type = $node['type'] ?? null;
            switch ($type) {
                case TYPES['PARENT']:
                    $children = makeStylish($node['children'], $depth + 1);
                    return  getIndent($depth, INDENTS_SIZE['BIG']) .
                        "{$node['key']}: " . "" . "{$children}";
                case TYPES['ADDED']:
                    $stringVal = stringifyInObjects($node['value'], $depth);
                    return getIndent($depth) . "+ {$node['key']}: {$stringVal}";
                case TYPES['DELETED']:
                    $stringVal = stringifyInObjects($node['value'], $depth);
                    return getIndent($depth) . "- {$node['key']}: {$stringVal}";
                case TYPES['UNMODIFIED']:
                    $stringVal = stringifyInObjects($node['value'], $depth);
                    return getIndent($depth) . "  {$node['key']}: {$stringVal}";
                case TYPES['MODIFIED']:
                    $newLine = getIndent($depth);
                    $oldValue = stringifyInObjects($node['before'], $depth);
                    $newValue = stringifyInObjects($node['after'], $depth);
                    return "{$newLine}- {$node['key']}: {$oldValue}\n"
                        . "{$newLine}+ {$node['key']}: {$newValue}";
                default:
                    throw new \Error("undefined type {$type}");
            }
        },
        $tree
    ));
    return "{\n{$result}\n" . getIndent($depth - 1, INDENTS_SIZE['BIG']) . "}";
}

/**
 * @param mixed $value
 * @param int $depth
 * @return string
 */
function stringifyInObjects($value, int $depth = 1): string
{
    if (!is_object($value)) {
        return toString($value);
    }
    $result = array_map(
        function ($key) use ($value, $depth): string {
            $stringifier = stringifyInObjects($value->$key, $depth + 1);
            return getIndent($depth + 1, INDENTS_SIZE['BIG']) . "{$key}: {$stringifier}";
        },
        array_keys((array) $value)
    );
    return "{\n" . implode(PHP_EOL, $result) . "\n" . getIndent($depth, INDENTS_SIZE['BIG']) . "}";
}

/**
 * @param int $depth
 * @param string $identType
 * @return string
 */
function getIndent(int $depth, string $identType = 'standardIdent'): string
{

    switch ($identType) {
        case 'standardIdent':
            return  str_repeat(INDENT, $depth * 4 - 2);
        case 'bigIdent':
            return str_repeat(INDENT, $depth * 4);
        default:
            throw new \Error("wrong ident type $identType");
    }
}

/**
 * @param mixed $value
 * @return string
 */
function toString($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return "null";
    }
    return "{$value}";
}
