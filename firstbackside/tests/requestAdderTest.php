<?php

use PHPUnit\Framework\TestCase;
use App\Services\requestAdder;
use App\Dto\DTO;

class RequestAdderTest extends TestCase
{
    public function testAddrequest()
    {
        $dto = new DTO(
            'Samsung',           // $brand
            'Galaxy S23',        // $name
            256,                 // $memory
            true,                // $isNew (замени 'new' на true/false)
            100,                 // $batteryCondition (тут должно быть число!)
            'Excellent',         // $caseCondition
            'Original',          // $screenCondition
            true,                // $isWorking
            'Fully functional',  // $workingDescription
            '79001112233',       // $userPhone
            'Иван Тестов',       // $userName
            3,                   // $equipment
            false                // $isRepair
        );
        $stmtMock = $this->createMock(\PDOStatement::class);
        $PDOmock = $this->createMock(\PDO::class);
        $Dbmock = $this->createMock(App\Db\DbInterface::class);
        $Dbmock->method('getConnection')->willReturn($PDOmock);
        $PDOmock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->willReturn(true);
        $manager = new requestAdder($Dbmock);
        $manager->addDataToDb($dto);

        $this->asserttrue(true);
    }
}
