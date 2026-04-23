<?php
namespace App\Services;
use app\Exceptions\AuthErr;
use PDO;

class authManager{
    protected $db;
    protected $login;
    protected $pass;



    public function __construct($db,$data)
    {
        $this->db = $db;
        $this->login = $data['login'];
        $this->pass = $data['pass'];
    }
    public function auth(){
        $sql = 'SELECT login, password_hash FROM admins WHERE login = :login';
        try{
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare($sql);
            $succ = $stmt->execute(['login' => $this->login]);
            if($succ){
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$res || !password_verify(($this->pass), $res['password_hash'])){
                    throw new authErr('verif err');
                }
            }
        }
        catch(authErr $e){
            error_log('AUTH ERROR'. $e->getMessage());
            return false;
        }
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['auth'] = true;
        $_SESSION['is_admin'] = '1';
        $_SESSION['user_login'] = $res['login'];
        return true;
    }
}


?>