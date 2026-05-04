<?php
namespace App\Services;
use App\Dto\DTO;
use App\Db\db;
use PDO;
use PDOException;
use App\Exceptions\DatabaseException;

class requestAdder {
//свойство защищено чтобы ограничить область использование объекта ПДО
    protected $db;
// The resulting database object is used in the constructor for subsequent DB methods.
    public function __construct(DB $db) {
        $this->db = $db;
    }
// A method for adding data to a database. It uses DTO for more convenient work with received and already validated data.
//блок try catch нужен для безопасности и контроля каждого запроса
         public function addDataToDb(DTO $dto) {
        try {
            $sql = 'INSERT INTO public.requests (
                        brand, 
                        device, 
                        memory_count, 
                        is_new, 
                        is_repair,
                        battery_condition, 
                        case_condition, 
                        screen_condition, 
                        is_working, 
                        working_description, 
                        equipment,
                        phone, 
                        name
                    ) VALUES (
                        :brand, 
                        :device, 
                        :memory_count, 
                        :is_new, 
                        :is_repair,
                        :battery_condition, 
                        :case_condition, 
                        :screen_condition, 
                        :is_working, 
                        :working_description, 
                        :equipment,
                        :phone, 
                        :name
                    )';

            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':brand'               => $dto->brand,
                ':device'              => $dto->name,             
                ':memory_count'        => $dto->memory,
                ':is_new'              => (int)$dto->isNew,
                ':is_repair'           => (int)$dto->isRepair,
                ':battery_condition'   => $dto->batteryCondition,
                ':case_condition'      => $dto->caseCondition,
                ':screen_condition'    => $dto->screenCondition,
                ':is_working'          => (int)$dto->isWorking,
                ':working_description' => $dto->workingDescription,
                ':equipment'          => $dto->equipment,
                ':phone'               => $dto->userPhone,        
                ':name'                => $dto->userName           
            ]);

        } catch (PDOException $e) {
            $errorData = [
                'time'    => date('Y-m-d H:i:s'),
                'level'   => 'critical',
                'message' => 'dbError',
                'details' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];
            error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));
            throw new DatabaseException('dbSaveInError');
        }
    }
}