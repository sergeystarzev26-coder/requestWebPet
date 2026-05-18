<?php

namespace App\Db;

use App\Exceptions\DatabaseException;
use PDOException;
use PDO;

class db implements DbInterface
{
    private $pdo;
    // метод создания подключения к БД. принимаиг ет как параметр конфигурацию из другого файла.

    public function __construct(array $config)
    {
        //создание внутренних свойств на основе конфигурации чтобы постоянно не образщаться к параметру конфига
        $host = $config['host'];
        $port = $config['port'];
        $db = $config['dbname'];
        $user = $config['user'];
        $pass = $config['pass'];
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        //создание подключение к базе данных через блок try catch тк подключение к базе данных является хрупким процессом
            $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
