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

    public function getAllRequests()
    {
        try {
            $sql = 'SELECT * FROM requests ORDER BY created_at DESC';
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $this->renderError($e, 403, 'dberror');
        }
    }

    public function deleteRequest(adminDto $adminDto)
    {
        try {
            $sql = 'DELETE FROM requests WHERE id = :id';

            $stmt = $this->db->getConnection()->prepare($sql);
            //данные для отправки в БД берутся из сформированного ДТО,как и во всем последующем коде
            $stmt->execute([
                ':id' => $adminDto->id,
            ]);
        } catch (PDOException $e) {
            return $this->renderError($e, 403, 'dberror');
        }
    }

    public function pauseRequest(adminDto $adminDto)
    {
        try {
            $sql = 'UPDATE requests SET isPause = true WHERE id = :id';
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $adminDto->id
            ]);
        } catch (PDOException $e) {
            return $this->renderError($e, 403, 'dberror');
        }
    }

    public function unpauseRequest(adminDto $adminDto)
    {
        try {
            $sql = 'UPDATE requests SET "isPause" = false WHERE id = :id';
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $adminDto->id,
            ]);
        } catch (PDOException $e) {
            return $this->renderError($e, 403, 'dberror');
        }
    }

    public function findRequest(adminDto $adminDto)
    {
        try {
            $sql = 'SELECT * FROM requests WHERE id = :id';
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $adminDto->id,
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $this->renderError($e, 403, 'dberror');
        }
    }

    private function renderError(Exception $e, int $httpCode, string $publicMessage): string
    {
        http_response_code($httpCode);

        $errorData = [
            'time'    => date('Y-m-d H:i:s'),
            'level'   => 'critical',
            'message' => 'unexpected err',
            'details' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));

        return json_encode([
            'status'  => 'error',
            'message' => $publicMessage
        ]);
    }
}
