<?php
$config = require __DIR__ . '/../../../config/config.php';
$host = $config['db']['host'];
$db   = $config['db']['dbname'];
$user = $config['db']['user'];
$pass = $config['db']['pass'];

try {
    $dsn = "pgsql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE TABLE IF NOT EXISTS public.migrations (
        id SERIAL PRIMARY KEY,
        migration_name VARCHAR(255) NOT NULL UNIQUE,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    $files = scandir(__DIR__);
    $sqlfiles = array_filter($files, function ($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
    });
    sort($sqlfiles);

    $stmt = $pdo->query('SELECT migration_name FROM public.migrations');
    $appliedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $executedCount = 0;

    foreach ($sqlfiles as $file) {
        if (!in_array($file, $appliedMigrations)) {
            echo "выполняется миграция $file..";
            $sql = file_get_contents(__DIR__ . '/' . $file);
            $pdo->exec($sql);
            $insertStmt = $pdo->prepare("INSERT INTO public.migrations (migration_name) VALUES (?)");
            $insertStmt->execute([$file]);
            echo "Успешно применена!<br>";
            $executedCount++;
        }
    }
    if ($executedCount === 0) {
        echo 'все миграции уже применены.';
    }
} catch (PDOException $e) {
    exit('ошибка базы данных.' . $e->getMessage());
}
