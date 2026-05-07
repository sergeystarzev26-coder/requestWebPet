<?php
use PHPUnit\Framework\TestCase;
use App\Db\db;

class dbTest extends TestCase{
    private array $validConfig;

    protected function setUp(): void
    {

        $this->validConfig = [
            'db' => [
                'host'   => 'localhost',
                'port'   => '5432',
                'dbname' => 'test_db',
                'user'   => 'postgres',
                'pass'   => 'Stavropol26'
            ]
        ];
    }
  public function testValidConfig(): void
{
        $db = new db($this->validConfig);
        $this->assertInstanceOf(PDO::class, $db->getConnection());

}
    public function testInvakidConfig(): void
    {
        $this->expectException(App\Exceptions\dbAdminErr::class);
        $invalidConfig = $this->validConfig;
        $invalidConfig['db']['port'] = '9999';
        $db = new db($invalidConfig);
    }
}