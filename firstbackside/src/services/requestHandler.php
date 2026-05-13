<?php

namespace App\Services;

use App\Exceptions\HandlerException;
use Exception;

class requestHandler
{
    public static function takeDataFromPost(?string $source = null): array
    {
        $rawData = $source ?? file_get_contents('php://input');

        if (empty($rawData)) {
            throw new HandlerException('empty request');
        }

        $decodeData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HandlerException('postData != Json');
        }

        if ($decodeData === null || !is_array($decodeData)) {
            throw new HandlerException('invalid json structure');
        }

        return $decodeData;
    }
}
