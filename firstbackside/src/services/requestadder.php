<?php
namespace App\Services;
use App\Dto\DTO;
use App\Db\db;
use PDO;
use PDOException;
use App\Exceptions\DatabaseException;



class requestAdder {
    protected $db;

    public function __construct(DB $db) {
        $this->db = $db;
    }
    public function addDataToDb(DTO $dto) {
        try {

            $sql = 'INSERT INTO requests (
                        devicebrand, name, devicememory, isnew, 
                        batterycondition, casecondition, screencondition, 
                        isworking, workingdescription, userphone, 
                        username, equipment, isrepair
                    ) VALUES (
                        :brand, :device, :memory_count, :is_new, 
                        :battery_condition, :case_condition, :screen_condition, 
                        :is_working, :working_description, :phone, 
                        :userName, :equipment, :is_repair
                    )';


            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':brand'               => $dto->brand,
                ':device'              => $dto->name,
                ':memory_count'        => $dto->memory,
                ':is_new'              => (int)$dto->isNew,
                ':battery_condition'   => $dto->batteryCondition,
                ':case_condition'      => $dto->caseCondition,
                ':screen_condition'    => $dto->screenCondition,
                ':is_working'          => (int)$dto->isWorking,
                ':working_description' => $dto->workingDescription,
                ':phone'               => $dto->userPhone,
                ':userName'            => $dto->userName,
                ':equipment'           => $dto->equipment,
                ':is_repair'           => (int)$dto->isRepair
            ]);

        } catch (PDOException $e) {
            error_log('Ошибка БД: ' . $e->getMessage());
            throw new DatabaseException('Ошибка при сохранении в базу данных.');
        }
    }
}
