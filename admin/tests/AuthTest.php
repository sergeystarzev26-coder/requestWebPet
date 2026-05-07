<?php
use PHPUnit\Framework\TestCase;
use App\Services\authManager;




class AuthTest extends TestCase 
{
      protected function tearDown(): void
    {
        $_SESSION = [];
    }
   /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSuccessfulAuth()
    {
        // 1. Мокаем PDOStatement (самое глубокое звено)
        // Он нужен, потому что код вызывает $stmt->execute() и $stmt->fetch()
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn([
                     'login' => 'admin',
                     'password_hash' => password_hash('my_password', PASSWORD_DEFAULT)
                 ]);
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);
        
        $dbmock = $this->createMock(\App\Db\DbInterface::class); 
        $dbmock->method('getConnection')->willReturn($pdoMock);

         if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $userData = ['login' => 'admin', 'pass' => 'my_password'];
        @session_start();
        $authservice = new authManager($dbmock, $userData);
        $result = $authservice->auth();
        $this->assertTrue($result, "Авторизация должна пройти успешно");
        $this->assertEquals('admin', $_SESSION['user_login']);
        $this->assertEquals('1', $_SESSION['is_admin']);


    }
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAuthFailedWithWrongPassword()
    {
        // 1. Мокаем PDOStatement (самое глубокое звено)
        // Он нужен, потому что код вызывает $stmt->execute() и $stmt->fetch()
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn([
                     'login' => 'admin',
                     'password_hash' => password_hash('my_password', PASSWORD_DEFAULT)
                 ]);
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);
        
        $dbmock = $this->createMock(\App\Db\DbInterface::class); 
        $dbmock->method('getConnection')->willReturn($pdoMock);

         if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $userData = ['login' => 'admin', 'pass' => 'wrongpass'];
        @session_start();
        $authservice = new authManager($dbmock, $userData);
        $result = $authservice->auth();
        $this->assertFalse($result, "Авторизация должна не пройти");
        $this->assertArrayNotHasKey('auth', $_SESSION);

    }
}

