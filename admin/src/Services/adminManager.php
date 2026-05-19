<?php

namespace App\Services;

use App\Dto\adminDto;
use App\Exceptions\dbAdminErr;
use App\Exceptions\dbActionErr;
use App\Db\db;
use PDO;
use PDOException;
use Exception;
use App\Db\DbInterface;

class adminManager
{
    protected $db;

    // Менеджер операций берет данные о текущем действии пользователя и выполняет операцию с БД
    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    // ИСПРАВЛЕНО: Добавлен $adminDto в аргументы, чтобы метод видел ID
    public function Manage(adminDto $adminDto)
    {
        $action = $adminDto->action ?? 'list';
        switch ($action) {
            case 'delete':  
                $sql = 'DELETE FROM requests WHERE id = :id';

                $stmt = $this->db->getConnection()->prepare($sql);
                // данные для отправки в БД берутся из сформированного ДТО, как и во всем последующем коде
                $stmt->execute([
                    ':id' => $adminDto->id,
                ]);
                break;

            case 'pause':    
                $sql = 'UPDATE requests SET ispause = true WHERE id = :id';

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([
                    ':id' => $adminDto->id
                ]);
                break;

            case 'unpause':    
                $sql = 'UPDATE requests SET "ispause" = false WHERE id = :id';

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([
                    ':id' => $adminDto->id,
                ]);
                break;

            case 'find':    
                $sql = 'SELECT * FROM requests WHERE id = :id';

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([
                    ':id' => $adminDto->id,
                ]);

                return $stmt->fetch(PDO::FETCH_ASSOC);

            case 'list':
                $sql = 'SELECT * FROM requests ORDER BY created_at DESC';

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
