<?php
namespace App\Services;

use App\Db\DbInterface;
use App\Dto\loginDto;
use App\Services\authManager;
use App\Exceptions\AuthErr;

class authguard {
    protected DbInterface $db;
    protected loginDto $dto;

    public function __construct(DbInterface $db, loginDto $dto)
    {
        $this->db = $db;
        $this->dto = $dto;
    }

    public function authIfNeeded() {
        if (!empty($_SESSION['auth'])) {
            return true;
        }
        $auth = new authManager($this->db, $this->dto);
        
        if ($auth->auth()) {
            return true;
        }
        throw new AuthErr('input correct login or password');
    }
}
