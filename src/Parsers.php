<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function convert($data, $fileExt)
{
    switch ($fileExt) {
        case "json":
            $result = json_decode($data, true);
            break;
        case "yml":
            // no break
        case "yaml":
            try {
                $result = Yaml::parse($data);
            } catch (ParseException $exception) {
                printf('Unable to parse the YAML string: %s', $exception->getMessage());
            }
            break;
        default:
            throw new \Exception("Wrong file extension: {$fileExt}");
    }

    return $result;
}
