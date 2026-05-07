<?php
use App\Services\rolechecker;
use PHPUnit\Framework\TestCase;
class roleCheckerTest extends TestCase{
     /**
     * @runInSeparateProcess
     */
    public function testRoleCheck(){
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['is_admin'] = true;
        $_SESSION['auth'] = true;
        $this->assertTrue(rolechecker::checkIsadmin());
    }
     /**
     * @runInSeparateProcess
     */
    public function testRoleCheckException(){
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $_SESSION['is_admin'] = false;
        $_SESSION['auth'] = false;
        $this->assertFalse(rolechecker::checkIsadmin());
    }
}