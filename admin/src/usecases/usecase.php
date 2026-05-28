<?php

namespace App\usecases;

use App\Db\DbInterface;
use App\Dto\DtoInterface;
use App\Dto\Factories\DtoFactory;
use App\Dto\loginDto;
use App\Services\authguard;
use App\Services\adminRepository;
use App\Services\rolechecker;
use App\Mappers\adminMapper;
use App\Exceptions\RoleErr;

class usecase
{
    protected DbInterface $db;
    protected adminRepository $manager;

    public function __construct(DbInterface $db, adminRepository $manager)
    {
        $this->db = $db;
        $this->manager = $manager;
    }

    public function execute(array $data)
    {
        $dto = DtoFactory::fromArray($data);
        
        // 1. Если прилетел логин — проверяем пароль и выходим из метода (Early Return)
        if ($dto instanceof loginDto) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION = []; // Очищаем всё внутри сессии
            $authGuard = new authguard($this->db, $dto);
            $authGuard->authIfNeeded();
            return true; // Передаем управление в контроллер, проверка пройдена
        }
        
        // 2. Если это любой другой бизнес-запрос — строго проверяем сессию админа
        if (!rolechecker::checkIsadmin()) {
            throw new RoleErr('role error');
        }
        
        // 3. Выполняем действие в БД (list, find, pause, unpause, delete)
        return $this->manager->Manage($dto);
    }
}
