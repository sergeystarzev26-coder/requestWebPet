<?php

namespace App\Services;

use App\Db\DbInterface;
use App\Dto\loginDto; // 1. Обязательно импортируем наш DTO
use App\Exceptions\AuthErr;
use PDO;

class authManager
{
    protected DbInterface $db;
    protected loginDto $dto; 

    public function __construct(DbInterface $db, loginDto $dto)
    {
        $this->db = $db;
        $this->dto = $dto;
    }

    public function auth()
    {
        $sql = 'SELECT login, password_hash FROM admins WHERE login = :login';
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $succ = $stmt->execute(['login' => $this->dto->login]);
        
        if ($succ) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res || !password_verify($this->dto->password, $res['password_hash'])) {
                throw new AuthErr('verif err');
            }
        } else {
            throw new AuthErr('database error');
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['auth'] = true;
        $_SESSION['is_admin'] = '1';
        $_SESSION['user_login'] = $res['login'];
        
        return true;
    }
}
