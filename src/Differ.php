<?php

namespace Differ\Differ;

use function Functional\sort;

function genDiff(string $filepath1, string $filepath2): string
{
    $json1 = boolConverter(fileDecoder($filepath1));
    $json2 = boolConverter(fileDecoder($filepath2));
    $keys = array_keys(array_merge($json1, $json2));
    $sorted = sort($keys, fn($right, $left) => $right <=> $left, true);


    return array_reduce(
        $sorted,
        function ($acc, $key) use ($json1, $json2) {
            if (array_key_exists($key, $json1) && array_key_exists($key, $json2)) {
                if ($json1[$key] !== $json2[$key]) {
                    $acc .= " - {$key}: {$json1[$key]}\n";
                    $acc .= " + {$key}: {$json2[$key]}\n";
                } else {
                    $acc .= "   {$key}: {$json1[$key]}\n";
                }
            } elseif (array_key_exists($key, $json1) && !array_key_exists($key, $json2)) {
                $acc .= " - {$key}: {$json1[$key]}\n";
            } else {
                $acc .= " + {$key}: {$json2[$key]}\n";
            }
            return $acc;
        }
    );
}

function fileDecoder(string $path): array
{
    if (!file_exists($path)) {
        throw new \Error("{$path} file not exist");
    } else {
        $file = file_get_contents($path);
        return json_decode($file, true);
    }
}


function boolToString(bool $value): string
{
    return $value ? 'true' : 'false';
}

function boolConverter(array $array): array
{
    return array_map(fn($value) => is_bool($value) ? boolToString($value) : $value, $array);
}
