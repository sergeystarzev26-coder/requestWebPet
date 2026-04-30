<?php
namespace App\Services;

use App\Dto\adminDto;
use app\Exceptions\dbAdminErr;
use PDO;
use PDOException;
use app\Exceptions\dbActionErr;
use Exception;

class adminManager
{
    protected $db;
//менеджер операций берет данные о текущем действии пользователя и в заивисмости от него выполняет операцию с базой данных
    public function __construct(\App\Db\db $db)
    {
        $this->db = $db;
    }

    public function getAllRequests()
    {
        try {
            $sql = 'SELECT * FROM requests ORDER BY created_at DESC';
            
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute();
            /** @var \PDOStatement $stmt */
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            error_log('dberr: ' . $e->getMessage());
            throw new dbActionErr('getAllRequests method err');
        }
    }

    public function deleteRequest(adminDto $adminDto)
    {
        try {
            $sql = 'DELETE FROM requests WHERE id = :id';

            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':id' => $adminDto->id,
            ]);
        } catch (PDOException $e) {
            error_log('db err: ' . $e->getMessage());
            throw new dbAdminErr('deleteRequest method err');
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
            error_log('db err: ' . $e->getMessage());
            throw new dbAdminErr('pauseRequest method err');
        }
    }

    public function unpauseRequest(adminDto $adminDto)
    {
        try{
            $sql = 'UPDATE requests
                    SET "isPause" = true
                    WHERE id = :id';
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':id' => $adminDto->id,
            ]);
        }
        catch(PDOException $e){
            error_log('Ошибка БД: ' . $e->getMessage());
            throw new dbAdminErr('unpauseRequest method err');
        }
    }

    public function findRequest(adminDto $adminDto)
    {
    try{
        $sql = 'SELECT *
                FROM requests
                WHERE id = :id';
        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->execute([
            ':id' => $adminDto->id,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
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
?>
