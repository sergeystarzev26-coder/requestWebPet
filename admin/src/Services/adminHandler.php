<?php
namespace App\Services;

use App\Exceptions\inputErr;
use Exception;

class adminHandler {
    
    public static function takeDataFromPost(?string $source = null) {
        try {
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
                throw new \Exception('decode Data error');
            }

            return $decodeData;

        } catch (inputErr $e) {
            return self::renderError($e, 403, 'incorrect data');
        } catch (\PDOException $e) {
            return self::renderError($e, 500, 'database connection error');
        } catch (Exception $e) {
            return self::renderError($e, 500, 'unexpected error');
        }
    }

    private static function renderError(Exception $e, int $httpCode, string $publicMessage): string
    {
        if (!headers_sent()) {
            http_response_code($httpCode);
        }

        $errorData = [
            'time'    => date('Y-m-d H:i:s'),
            'level'   => 'critical',
            'details' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));

        return json_encode([
            'status'  => 'error',
            'message' => $publicMessage
        ]);
    }
}
