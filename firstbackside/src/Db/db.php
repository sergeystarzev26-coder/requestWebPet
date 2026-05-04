<?php

namespace App\Db;

require_once __DIR__ . '/config.php';

use App\Exceptions\DatabaseException;
use PDOException;
use PDO;

class db
{
    private $pdo;
    // метод создания подключения к БД. принимаиг ет как параметр конфигурацию из другого файла.

    public function __construct(array $config)
    {
        //создание внутренних свойств на основе конфигурации чтобы постоянно не образщаться к параметру конфига
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
        //создание подключение к базе данных через блок try catch тк подключение к базе данных является хрупким процессом
        try {
            $this->pdo = new PDO( $dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            //в блоке catch используется error_log для логирования ошибок. для отслеживания трейса ошибки создается массив.
            $errorData = [
                'time'    => date('Y-m-d H:i:s'),
                'level'   => 'critical',
                'message' => 'dberror',
                'details' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];

            error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));
            throw new DatabaseException("service error");
        }
    }

    public function getConnection() : PDO
    {
        return $this->pdo;
    }
}

?>
