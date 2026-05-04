<?php
require_once __DIR__ . '/requestweb/firstbackside/db.php';
require_once __DIR__ . '/admindto.php';
require_once __DIR__ . '/adminExceptions.php';


class adminManager
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllRequests($adminDto)
    {
        try {
            $sql = 'SELECT * FROM requests ORDER BY created_at DESC';
            
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            error_log('Ошибка БД: ' . $e->getMessage());
            throw new dbActionErr('Ошибка при getAllRequests'); 
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
            error_log('Ошибка БД: ' . $e->getMessage());
            throw new dbActionErr('Ошибка при удалении deleteRequest');
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
            error_log('Ошибка БД: ' . $e->getMessage());
            throw new dbActionErr('Ошибка при выполнении pauseRequest');
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
            throw new dbActionErr('Ошибка при выполнении unpauseRequest');
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
        throw new dbActionErr('Ошибка при выполнении findRequests');
    }
    }
}
?>
