<?php

namespace Differ\Formatters\Json;

function render(array $intStruct): string
{
    return json_encode($intStruct, JSON_THROW_ON_ERROR);
}
