<?php

namespace App\Services;

use App\Exceptions\inputErr;
use Exception;

class adminHandler
{

    public static function takeDataFromPost(?string $source = null) 
    {
            $rawData = $source ?? file_get_contents('php://input');
            if (empty($rawData)) {
                throw new inputErr('empty request');
            }

            $decodeData = json_decode($rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new inputErr('postData != Json');
            }

            if ($decodeData === null) {
                throw new inputErr('json_decodes returns NULL');
            }

            if (!is_array($decodeData)) {
                throw new inputErr('decode Data error');
            }
            return $decodeData;

    }
}