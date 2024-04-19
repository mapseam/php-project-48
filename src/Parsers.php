<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function convert($data, $format)
{
    switch ($format) {
        case "json":
            return json_decode($data, true);
        case "yml":
        case "yaml":
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Wrong file extension: {$format}");
    }
}
