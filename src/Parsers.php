<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @param string $extension
 * @param string $data
 * @return object
 */

function parser(string $extension, string $data): object
{
    switch ($extension) {
        case 'json':
            $result = json_decode($data);
            break;
        case 'yaml':
        case 'yml':
            $result = Yaml::parse($data, YAML::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Error("Unknown extension $extension");
    }
    return  $result;
}
