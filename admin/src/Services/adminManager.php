<?php
namespace App\Services;

use app\Exceptions\dbAdminErr;
use PDO;
use PDOException;
use app\Exceptions\dbActionErr;


class adminManager
{
    protected $db;

    public function __construct($db)
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
        }
        catch (PDOException $e){
            error_log('dberr: ' . $e->getMessage());
            throw new dbActionErr('getAllRequests method err');
        }
    }

    public function deleteRequest($adminDto)
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

    public function pauseRequest($adminDto)
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

    public function unpauseRequest($adminDto)
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

    public function findRequest($adminDto)
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
        error_log('Ошибка БД: ' . $e->getMessage());
        throw new dbAdminErr('findRequests method err');
    }
    }
}
?>
