<?php
use PDO;
use App\Db\db;
header('Content-Type: application/json; charset=utf-8');
$config = require_once __DIR__ . '/../../config/config.php';
$response = [
    'status' => 'ok',
    'http_code' => 200,
    'timestamp' => time(),
    'services' => [],
];
$isHealthy = true;
try{
    $dbConnection = (new db($config))->getConnection();
    $stmt = $dbConnection->query('SELECT 1');
    $response['services']['database'] = 'ok';
}
catch (Throwable $e) {
    $response['services']['database'] = 'FAIL';
    $isHealthy = false;
}
if(!$isHealthy){
header("HTTP/1.1 500 Internal Server Error");
    $response['status'] = 'Error';
     $response['http_code'] = 500;
}
else {
    header("HTTP/1.1 200 OK");
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);