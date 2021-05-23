<?php

namespace Differ\Differ\Json;

function makeJson(array $tree): string
{
    return json_encode($tree, JSON_FORCE_OBJECT);
}
