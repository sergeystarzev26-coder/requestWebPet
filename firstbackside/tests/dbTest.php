<?php

use PHPUnit\Framework\TestCase;
use App\Db\db;
use PDO;

class dbTest extends TestCase
{
    private array $validConfig;

    protected function setUp(): void
    {

        $this->validConfig = [
            'db' => [
                'host'   => 'localhost',
                'port'   => '5432',
                'dbname' => 'postgres',
                'user'   => 'postgres',
                'pass'   => 'Stavropol26'
            ]
        ];
    }
    public function testValidConfig(): void
    {
        $db = new db($this->validConfig['db']);
        $this->assertInstanceOf(PDO::class, $db->getConnection());
    }
    public function testInvalidConfig(): void
    {
        $this->expectException(App\Exceptions\DatabaseException::class);
        $invalidConfig = $this->validConfig['db'];
        $invalidConfig['port'] = '9999';
        $db = new db($invalidConfig);
    }
}
