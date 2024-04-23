<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function convert($data, $fileExt)
{
    switch ($fileExt) {
        case "json":
            return json_decode($data, true);
        case "yml":
            // no break;
        case "yaml":
            try {
                return Yaml::parse($data);
            } catch (ParseException $exception) {
                printf('Unable to parse the YAML string: %s', $exception->getMessage());
            }
        default:
            throw new \Exception("Wrong file extension: {$fileExt}");
    }
}
