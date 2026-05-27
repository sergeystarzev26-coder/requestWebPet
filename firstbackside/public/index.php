<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';
use App\Controllers\Controller;
use App\Services\requestAdder;
use App\Db\db;
use App\usecase\usecase;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status'  => 'error',
        'message' => 'Method Not Allowed. Only POST requests are accepted.'
    ]);
    exit;
    }

// Входная публичная точка входа. Вызывает контроллер и отдает результат в формате JSON

try {

        $db = new db($config);
        $manager = new requestAdder($db);
        $usecase = new usecase($db, $manager);

        // ИСПРАВЛЕНО: Создаем экземпляр контроллера и передаем в него собранный UseCase
        $controller = new Controller($usecase);

        $response = $controller->execute();

        echo $response;
    } catch (\Throwable $e) {
        // Отдаю код на фронтенд при ошибке.
        http_response_code(500);

        $errorData = [
            'time'    => date('Y-m-d H:i:s'),
            'level'   => 'critical',
            'message' => 'unexpected error',
            'details' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];
        // Логирую в формате json для удобства отладки ошибок.
        error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));

        echo json_encode([
            'status'  => 'error',
            'message' => 'Critical system error'
        ]);
    }

