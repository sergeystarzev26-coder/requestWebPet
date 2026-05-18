<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Db\db;
$config = require __DIR__ . '/../../../config/config.php';

$login = 'amazingAdmin431';
$role = 'admin';
$rawpass = 'testpass948';
$password_hash = password_hash($rawpass, PASSWORD_BCRYPT);
try{
    $dbConnection = (new db($config))->getConnection();
    $sql = 'INSERT INTO admins (login, role, password_hash) VALUES(:login, :role, :password_hash)';
    $stmt = $dbConnection->prepare($sql);
     $stmt->execute([
        'login'         => $login,
        'role'          => $role,
        'password_hash' => $password_hash // Передаем именно хэш, а не сырой пароль
    ]);
    echo 'succsess';
}
catch (\PDOException $e) {
    if ($e->getCode() == 23505) { // Код ошибки уникальности для PostgreSQL
        echo "Error: this login already use";
    } else {
        echo "Error: Database error" . $e->getMessage();
    }
}