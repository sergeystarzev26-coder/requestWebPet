<?php

use PHPUnit\Framework\TestCase;
use App\Services\requestHandler;

class HandlerTest extends TestCase
{

    public function testTakeDataSuccess()
    {
        $validData = [
            'brand'             => 'Apple',           // строка до 16 симйв.
            'device'            => 'iPhone 13 Pro',   // строка до 32 симв.
            'memory_count'      => 256,               // число от 1 до 4096
            'battery_condition' => 95,                // число от 0 до 100
            'is_new'            => false,             // строго boolean
            'is_working'        => true,              // строго boolean
            'case_condition'    => 'Excellent',       // строка до 256 симв.
            'screen_condition'  => 'Original',        // строка до 256 симв.
            'working_description' => 'Minor scratches', // строка до 256 симв.
            'phone'             => '89991234567',     // строка до 15 симв.
            'name'              => 'Иван Иванов',      // строка до 20 симв.
            'equipment'         => 3,                 // число от 0 до 10
            'is_repair'         => false              // строго boolean
        ];

        $validJson = json_encode($validData);

        $result = requestHandler::takeDataFromPost($validJson);

        $this->assertIsArray($result);
        $this->assertEquals('Apple', $result['brand']);
    }

    // Тестируем битый JSON 
    public function testTakeDataEmpty()
    {
        // Говорим PHPUnit, что мы ждем ошибку inputErr
        $this->expectException(\App\Exceptions\HandlerException::class);
        $this->expectExceptionMessage('empty request');

        // Вызываем метод. Код после этой строчки не выполнится, 
        // PHPUnit сам поймает ошибку и пометит тест как пройденный.
        requestHandler::takeDataFromPost('');
    }

    // Тестируем битый JSON
    public function testTakeDataInvalidJson()
    {
        $this->expectException(\App\Exceptions\HandlerException::class);
        $this->expectExceptionMessage('postData != Json');

        requestHandler::takeDataFromPost('{ "brand": 123, broken... }');
    }
}
