<?php

require_once __DIR__ . '/vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';

use App\Controllers\Controller;
use App\Services\requestAdder;
use App\Db\db;
use App\usecase\usecase;

// Входная публичная точка входа. Вызывает контроллер и отдает результат в формате JSON
try {
    header('Content-Type: application/json');

    $db = new db($config);
    $manager = new requestAdder($db);
    $usecase = new usecase($db, $manager);
    
    // ИСПРАВЛЕНО: Создаем экземпляр контроллера и передаем в него собранный UseCase
    $controller = new Controller($usecase);
    
    $response = $controller->execute();

    echo $response;
} catch (\Exception $e) {
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
