<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

use app\Controllers\AdminController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $controller = new AdminController($config);
        
        echo $controller->execute();

    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Critical system error'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'ready',
        'message' => 'Admin API is running'
    ]);
}
