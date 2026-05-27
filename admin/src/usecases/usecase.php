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
        if ($dto instanceof loginDto) {
            $authGuard = new authguard($this->db, $dto);
            $authGuard->authIfNeeded();
            echo json_encode([
                'status' => 'success',
                'message' => 'Auth ok'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (!rolechecker::checkIsadmin()) {
            throw new RoleErr('role error');
        }
        return $this->manager->Manage($dto);
    }
}
