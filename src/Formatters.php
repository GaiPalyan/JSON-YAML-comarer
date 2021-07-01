<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

use function Differ\Differ\Plain\makePlain;
use function Differ\Differ\Stylish\makeStylish;
use function Differ\Differ\Json\makeJson;
use function Differ\Differ\treeBuilder;

/**
 * @param object $file1
 * @param object $file2
 * @param string $format
 * @return string
 * @throws \Exception
 */

function getFormat(object $file1, object $file2, string $format): string
{
    $tree = treeBuilder($file1, $file2);
    switch ($format) {
        case 'stylish':
            return makeStylish($tree);
        case 'plain':
            return makePlain($tree);
        case 'json':
            return makeJson($tree);
        default:
            throw new \Exception("Unsupported format $format");
    }
}
