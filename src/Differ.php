<?php

namespace Differ\Differ;

use function Differ\Differ\Formatters\getFormat;
use function Differ\Parsers\parser;
use function Functional\sort;

/**
 * @param string $filePath1
 * @param string $filePath2
 * @param string $format
 * @return string
 * @throws \Exception
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
                    'type' => 'added',
                    'value' => $file2->$key
                ];
            }
            if (!property_exists($file2, $key)) {
                return [
                  'key' => $key,
                  'type' => 'delete',
                  'value' => $file1->$key
                ];
            }
            if (is_object($file1->$key) && is_object($file2->$key)) {
                return [
                  'key' => $key,
                  'type' => 'parent',
                  'children' => treeBuilder($file1->$key, $file2->$key)
                ];
            }
            if ($file1->$key === $file2->$key) {
                return [
                  'key' => $key,
                  'type' => 'unchanged',
                  'value' => $file1->$key
                ];
            }
            return [
              'key' => $key,
              'type' => 'modified',
              'before' => $file1->$key,
              'after' => $file2->$key
            ];
        },
        $sorted
    );
}

/**
 * @param string $path
 * @return string
 */

function fileReader(string $path): string
{
    if (is_readable($path)) {
        $data = file_get_contents($path);
    } else {
        throw new \Error('unreadable statement');
    }
    return $data;
}

/**
 * @param string $path
 * @return object
 */

function getData(string $path): object
{
    if (file_exists($path)) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $content = fileReader($path);
        return parser($extension, $content);
    } else {
        throw new \Error('file not exist');
    }
}
