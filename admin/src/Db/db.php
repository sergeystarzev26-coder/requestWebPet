<?php
namespace App\Db;
use PDOException;
require_once __DIR__ . '/config.php';
use app\Exceptions\dbAdminErr;


use PDO;

class db{
 private $pdo;
 
 public function __construct(array $config)
 {
    $host = $config['db']['host'];
    $port = $config['db']['port'];
    $db = $config['db']['dbname'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
    ];

    try{
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }
    catch(PDOException $e){
        error_log("критическая ошибка БД" . $e->getMessage());
        throw new dbAdminErr("Сервис временно недоступен из-за проблем с БД.");
    }
 }
 public function getConnection(){
    return $this->pdo;
 }
}
?>