<?php

namespace Differ\Differ;

use function Differ\Parsers\parser;
use function Functional\sort;

function genDiff(string $filepath1, string $filepath2): string
{
    $file1 = boolConverter(parser($filepath1));
    $file2 = boolConverter(parser($filepath2));
    $keys = array_keys(array_merge($file1, $file2));
    $sorted = sort($keys, fn($right, $left) => $right <=> $left, true);


    $aggregatedDiff = array_reduce(
        $sorted,
        function ($acc, $key) use ($file1, $file2) {
            if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
                if ($file1[$key] !== $file2[$key]) {
                    $acc .= "  - {$key}: {$file1[$key]}\n";
                    $acc .= "  + {$key}: {$file2[$key]}\n";
                } else {
                    $acc .= "    {$key}: {$file1[$key]}\n";
                }
            } elseif (array_key_exists($key, $file1) && !array_key_exists($key, $file2)) {
                $acc .= "  - {$key}: {$file1[$key]}\n";
            } else {
                $acc .= "  + {$key}: {$file2[$key]}\n";
            }
            return $acc;
        }
    );
    return "{\n$aggregatedDiff}";
}

function boolToString(bool $value): string
{
    return $value ? 'true' : 'false';
}

function boolConverter(array $array): array
{
    return array_map(fn($value) => is_bool($value) ? boolToString($value) : $value, $array);
}
