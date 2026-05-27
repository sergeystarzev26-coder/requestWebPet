<?php

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

use App\usecases\usecase;
use App\Controllers\AdminController;
use App\Db\db;
use App\Services\adminRepository;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

session_start();

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

try {
    $dbConnection = new db($config);
    $manager = new adminRepository($dbConnection);
    $usecase = new usecase($dbConnection, $manager);
    $controller = new AdminController($usecase);

    echo $controller->execute();
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Critical system error'
    ]);
    error_log("BOOTSTRAP ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}
