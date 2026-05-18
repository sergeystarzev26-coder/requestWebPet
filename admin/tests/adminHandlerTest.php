<?php

use PHPUnit\Framework\TestCase;
use App\Services\adminHandler;

class adminHandlerTest extends TestCase
{

    public function testTakeDataSuccess()
    {
        $validJson = json_encode(['id' => 123, 'status' => 'ok']);

        $result = adminHandler::takeDataFromPost($validJson);

        $this->assertIsArray($result);
        $this->assertEquals(123, $result['id']);
    }

    // Тестируем битый JSON 
    public function testTakeDataInvalidJson()
    {
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('postData != Json');
        adminHandler::takeDataFromPost('{ "id": 123, broken... }');
    }

    // Тестируем пустой запрос
    public function testTakeDataEmpty()
    {
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('empty request');
        $result = adminHandler::takeDataFromPost('');

    }

    // Тестируем не массив 
    public function testTakeDataNotArray()
    {
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('decode Data error');
        $result = adminHandler::takeDataFromPost('12345');
    }
}
