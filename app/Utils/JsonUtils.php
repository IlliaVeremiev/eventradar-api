<?php

namespace App\Utils;

class JsonUtils
{
    public static function decode(string $json): array
    {
        return json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
    }
}
