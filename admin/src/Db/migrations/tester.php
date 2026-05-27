<?php

// 1. Подтягиваем конфиг и доступы точно так же, как в твоем коде
$config = require_once __DIR__ . '/../../../config/config.php';
$host = $config['db']['host'];
$db   = $config['db']['dbname'];
$user = $config['db']['user'];
$pass = $config['db']['pass'];

// Данные нового админа
$adminLogin = 'admin';
$adminPassword = 'rootpass'; // <--- ЗАДАЙ СВОЙ ПАРОЛЬ ТУТ
$adminRole = 'administrator';

try {
    // 2. Подключаемся к PostgreSQL через PDO
    $dsn = "pgsql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // 3. Проверяем, нет ли уже админа с таким логином, чтобы не плодить дубликаты
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM public.admins WHERE login = ?");
    $checkStmt->execute([$adminLogin]);
    $adminExists = (int)$checkStmt->fetchColumn() > 0;

    if ($adminExists) {
        exit("Пользователь с логином '{$adminLogin}' уже существует в базе данных.<br>");
    }

    // 4. Хешируем пароль самым надежным для PHP способом (bcrypt)
    $passwordHash = password_hash($adminPassword, PASSWORD_BCRYPT);

    // 5. Готовим SQL-запрос на вставку
    $sql = "INSERT INTO public.admins (
                login, 
                role, 
                password_hash, 
                created_at, 
                updated_at
            ) VALUES (
                :login, 
                :role, 
                :password_hash, 
                NOW(), 
                NOW()
            )";

    $stmt = $pdo->prepare($sql);
    
    // 6. Выполняем безопасный запрос
    $stmt->execute([
        ':login'         => $adminLogin,
        ':role'          => $adminRole,
        ':password_hash' => $passwordHash
    ]);

    echo "Администратор '{$adminLogin}' успешно создан!<br>";
    echo "Используй пароль: <b>{$adminPassword}</b> для входа в Postman.<br>";

} catch (PDOException $e) {
    exit('Ошибка базы данных: ' . $e->getMessage());
}
