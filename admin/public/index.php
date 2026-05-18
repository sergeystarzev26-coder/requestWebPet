<?php
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

use App\Controllers\AdminController;
use App\Db\db;
use App\Services\adminManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $dbConnection = new db($config);
        $manager = new adminManager($dbConnection);
        $controller = new AdminController($config, $dbConnection, $manager);

        echo $controller->execute();
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Critical system error: '
        ]);
        error_log("BOOTSTRAP ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    }
} else {
    echo json_encode([
        'status' => 'ready',
        'message' => 'Admin API is running'
    ]);
}
