<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parser(string $path): array
{
    if (!file_exists($path)) {
        throw new \Error("{$path} file not exist");
    } else {
        $file = file_get_contents($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'json':
                $result = json_decode($file);
                break;
            case 'yaml':
                $result = Yaml::parse($file, YAML::PARSE_OBJECT_FOR_MAP);
                break;
            default:
                throw new \Error("Unknown extension $extension");
        }
    }
    return is_object($result) ? convert($result) : $result;
}

function convert(object $obj): array
{
    return json_decode(json_encode($obj), true);
}
