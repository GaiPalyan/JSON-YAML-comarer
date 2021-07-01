<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;

use function Differ\Differ\Formatters\getFormat;
use function Differ\Parsers\parser;
use function Functional\sort;

use const Differ\Differ\Constants\TYPES;

/**
 * @param string $filePath1
 * @param string $filePath2
 * @param string $format
 * @return string
 * @throws Exception
 */

function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $file1 = getData($filePath1);
    $file2 = getData($filePath2);
    return getFormat($file1, $file2, $format);
}

/**
 * @param object $file1
 * @param object $file2
 * @return array
 */

function treeBuilder(object $file1, object $file2): array
{
    $keys = array_keys(array_merge((array)$file1, (array)$file2));
    $sorted = sort($keys, fn($left, $right) => $left <=> $right);

    return array_map(
        function ($key) use ($file1, $file2) {
            if (!property_exists($file1, $key)) {
                return [
                    'key' => $key,
                    'type' => TYPES['ADDED'],
                    'value' => $file2->{$key}
                ];
            }
            if (!property_exists($file2, $key)) {
                return [
                  'key' => $key,
                  'type' => TYPES['DELETED'],
                  'value' => $file1->{$key}
                ];
            }
            if (is_object($file1->{$key}) && is_object($file2->{$key})) {
                return [
                  'key' => $key,
                  'type' => TYPES['PARENT'],
                  'children' => treeBuilder($file1->{$key}, $file2->{$key})
                ];
            }
            if ($file1->{$key} === $file2->{$key}) {
                return [
                  'key' => $key,
                  'type' => TYPES['UNMODIFIED'],
                  'value' => $file1->{$key}
                ];
            }
            return [
              'key' => $key,
              'type' => TYPES['MODIFIED'],
              'before' => $file1->{$key},
              'after' => $file2->{$key}
            ];
        },
        $sorted
    );
}

/**
 * @param string $path
 * @return string
 * @throws Exception
 */

function fileReader(string $path): string
{
    if (is_readable($path)) {
        $content =  file_get_contents($path);
    } else {
        throw new Exception('unreadable file content');
    }
    if (is_string($content)) {
        return $content;
    } else {
        throw new Exception('content not in string format');
    }
}

/**
 * @param string $path
 * @return object
 * @throws Exception
 */

function getData(string $path): object
{
    if (file_exists($path)) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $content = fileReader($path);
        return parser($extension, $content);
    } else {
        throw new Exception('file not exist');
    }
}
