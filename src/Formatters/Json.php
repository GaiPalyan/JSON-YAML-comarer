<?php

namespace Differ\Differ\Json;

/**
 * @param array $tree
 * @return string
 */
function makeJson(array $tree): string
{
    return json_encode($tree, JSON_FORCE_OBJECT);
}
