<?php
namespace App\usecases;

use App\Db\DbInterface;
use App\Services\authguard;
use App\Services\adminManager;
use App\Services\rolechecker;
use App\Mappers\adminMapper;
use App\Exceptions\RoleErr;
class usecase{
    protected DbInterface $db;
    protected adminManager $manager;
    public function __construct(DbInterface $db, adminManager $manager)
    {
        $this->db = $db;
        $this->manager = $manager;
    }
    public function execute(array $data){
        $authGuard = new authguard($this->db, $data);
        $authGuard->authIfNeeded();
        if(!rolechecker::checkIsadmin()){
            throw new RoleErr('role error');
        }
        $dto = adminMapper::fromArray($data);
        return $this->manager->Manage($dto);
    }
}
