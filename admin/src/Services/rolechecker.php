<?php
namespace App\Services;

class rolechecker{
    public static function checkIsadmin(){
        
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $isAuth = $_SESSION['auth'] ?? false;
        $isAdmin = $_SESSION['is_admin'] ?? false;
        if($isAdmin && $isAuth == true){
            return true;
        }
            else{
                return false;
            }
    }
}
?>