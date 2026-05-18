<?php

namespace App\Db;

use PDOException;
use App\Exceptions\dbAdminErr;
use App\Db\DbInterface;

use PDO;

class db implements DbInterface
{
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

        
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        
    }
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
