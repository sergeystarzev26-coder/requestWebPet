<?php
namespace App\usecase;
use App\Db\DbInterface;
use App\Services\requestHandler;
use App\Mappers\Mapper;
use App\Services\validator;
use App\Services\requestAdder;
use App\Exceptions\ValidationException;
use App\Exceptions\DatabaseException;
use App\Services\errorcathcer;
use Exception;
class usecase{
    protected $db;
    protected $manager;
    public function __construct(DbInterface $db, requestAdder $manager)
    {
        $this->db = $db;
        $this->manager = $manager;
    }
    public function execute(array $data){
        validator::validateData($data);
        $dto = Mapper::fromArray($data);
        $this->manager->addDataToDb($dto);
    }
}