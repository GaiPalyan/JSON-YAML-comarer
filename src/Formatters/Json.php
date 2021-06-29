<?php

namespace Differ\Differ\Json;

use Exception;

/**
 * @param array $tree
 * @return string
 * @throws Exception
 */
function makeJson(array $tree): string
{
    $result = json_encode($tree, JSON_FORCE_OBJECT);
    if (is_string($result)) {
        return $result;
    } else {
        throw new Exception('failed to encode to Json');
    }
}
