<?php
require_once __DIR__ . '/vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';

use App\controllers\Controller;

try {
    $controller = new Controller($config);

    $response = $controller->execute();

    header('Content-Type: application/json');

    echo $response;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Critical system error'
    ]);
}
