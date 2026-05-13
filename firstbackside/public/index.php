<?php
require_once __DIR__ . '/vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';

use App\Controllers\Controller;
use App\Services\requestAdder;
use App\Db\db;

//Входная публичная точка входа.вызывает контроллер и отдает результат в формате JSON
try {
    header('Content-Type: application/json');

    $db = new db($config['db']);
    $requestAdder = new requestAdder($db);
    $controller = new Controller($config, $db, $requestAdder);

    $response = $controller->execute();

    echo $response;
} catch (\Exception $e) {
    //отдаю код на фронтенд при ошибке.
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
    //логирую в формате json для удобства откладки ошибок.
    error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));

    echo json_encode([
        'status'  => 'error',
        'message' => 'Critical system error'
    ]);
}
