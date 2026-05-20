<?php
namespace App\Services;

use App\Db\db;
use App\Db\DbInterface;
use App\Services\authManager;
use App\Exceptions\AuthErr;
class authguard{
    protected $db;
    protected $data;
    public function __construct(DbInterface $db, array $data)
    {
        $this->db = $db;
        $this->data = $data;
    }
    public function authIfNeeded(){
        if(!empty($_SESSION['auth'])){return true;}

        if(isset($this->data['login']) && isset($this->data['pass'])){
            $auth = new authManager($this->db, $this->data);
            return $auth->auth();
        }
    throw new AuthErr('input correct login or password');
    }
}