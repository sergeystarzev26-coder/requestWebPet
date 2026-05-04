<?php
use PHPUnit\Framework\TestCase;
use App\Services\adminManager;
use App\Dto\adminDto;




class adminManagerTest extends TestCase 
{
public function testfetchAll(){
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
public function testFindrequest(){
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
}

