<?php

namespace App\Services;

use PhpParser\Builder\Method;
use Throwable;

class Errorcathcer
{
    public static function error(Throwable $e, int $httpCode, string $publicMessage): string
    {
        if (!headers_sent()) {
            http_response_code($httpCode);
            header('Content-Type: application/json; charset=utf-8');
        }

        $errorData = [
            'level'   => 'critical',
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'details' => $e->getMessage(),
        ];

        error_log($publicMessage . ': ' . json_encode($errorData, JSON_UNESCAPED_UNICODE));

        return json_encode([
            'status' => 'error',
            'message' => $publicMessage
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
