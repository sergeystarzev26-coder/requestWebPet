<?php

use PHPUnit\Framework\TestCase;
use Mockery as m;
use App\Controllers\AdminController;
use App\Db\db;
use App\Services\adminManager;

class AdminControllerTest extends TestCase
{
    private array $config;

    protected function setUp(): void
    {
        // Конфиг может быть пустым, так как саму базу мы мокаем
        $this->config = [
            'db' => [
                'host' => 'localhost',
                'port' => '5432',
                'dbname' => 'test',
                'user' => 'user',
                'pass' => 'pass'
            ]
        ];
    }
    #[Override]
    protected function tearDown(): void
    {
        m::close();
        $_POST = [];
    }
}
