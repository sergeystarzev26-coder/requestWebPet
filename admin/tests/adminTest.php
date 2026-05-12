<?php

use PHPUnit\Framework\TestCase;
use App\Services\adminManager;
use App\Dto\adminDto;




class adminTest extends TestCase
{
    public function testfetchAll()
    {
        $fakeData = [['id' => 1, 'task' => 'Test']];
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('fetchAll')->willReturn($fakeData);

        $manage = new adminManager($Dbmock);
        $result = $manage->getAllRequests();
        $this->assertIsArray($result);
        $this->assertEquals(1, $result[0]['id']);
    }
    public function testFindrequest()
    {
        $dto = new adminDto(action: 'pause', id: 42);

        $fakeData = ['id' => 42, 'name' => 'Specific Request'];
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('fetch')->willReturn($fakeData);
        $manager = new adminManager($Dbmock);
        $result = $manager->findRequest($dto);

        $this->assertEquals('Specific Request', $result['name']);
    }
    public function testDeleteRequest()
    {
        $dto = new adminDto(action: 'delete', id: 42);
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':id' => 42])->willReturn(true);
        $manage = new adminManager($Dbmock);
        $manage->deleteRequest($dto);
    }
    public function testPauseRequest()
    {
        $dto = new adminDto(action: 'pause', id: 42);
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':id' => 42])->willReturn(true);
        $manage = new adminManager($Dbmock);
        $manage->pauseRequest($dto);
    }
    public function testUnPauseRequest()
    {
        $dto = new adminDto(action: 'unpause', id: 42);
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':id' => 42])->willReturn(true);
        $manage = new adminManager($Dbmock);
        $manage->unpauseRequest($dto);
    }
    public function testRenderErr()
    {
        $dto = new adminDto(action: 'unpause', id: 42);
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':id' => 42])->willThrowException(new PDOException('db is dead', 500));
        $manage = new adminManager($Dbmock);
        $result = $manage->deleteRequest($dto);
        $expectedJson = json_encode(['status' => 'error', 'message' => 'dberror']);
        $this->assertEquals($expectedJson, $result);
    }
}
