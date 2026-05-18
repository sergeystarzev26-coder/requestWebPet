<?php

$envPath = dirname(__DIR__) . '/.env';

// Загружаем данные из .env в массив $_ENV
if (file_exists($envPath)) {
    //парсим файл и при успехе вклеиваем его в $_ENV
    $env = parse_ini_file($envPath);
    if ($env) {
        $_ENV = array_merge($_ENV, $env);
    }
}

return [
    'db' => [
        'host'   => $_ENV['DB_HOST'] ?? 'localhost',
        'port'   => $_ENV['DB_PORT'] ?? '5432',
        'dbname' => $_ENV['DB_NAME'] ?? '',
        'user'   => $_ENV['DB_USER'] ?? '',
        'pass'   => $_ENV['DB_PASS'] ?? '',
    ],

    'app' => [
        'debug'     => (bool)($_ENV['APP_DEBUG'] ?? true),
        'logs_path' => __DIR__ . '/logs.txt',
    ],
];
