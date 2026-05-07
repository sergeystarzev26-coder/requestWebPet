<?php
use PHPUnit\Framework\TestCase;
use App\Services\adminHandler;

class adminHandlerTest extends TestCase {

    public function testTakeDataSuccess() {
        $validJson = json_encode(['id' => 123, 'status' => 'ok']);
        
        $result = adminHandler::takeDataFromPost($validJson);
        
        $this->assertIsArray($result);
        $this->assertEquals(123, $result['id']);
    }

    // Тестируем битый JSON 
    public function testTakeDataInvalidJson() {
        $result = adminHandler::takeDataFromPost('{ "id": 123, broken... }');
        
        $this->assertJson($result);
        $this->assertStringContainsString('incorrect data', $result);
    }

    // Тестируем пустой запрос
    public function testTakeDataEmpty() {
        $result = adminHandler::takeDataFromPost('');
        
        $this->assertJson($result);
        $this->assertStringContainsString('incorrect data', $result);
    }

    // Тестируем не массив 
    public function testTakeDataNotArray() {
        $result = adminHandler::takeDataFromPost('12345');
        
        $this->assertJson($result);
        $this->assertStringContainsString('unexpected error', $result);
    }
}
