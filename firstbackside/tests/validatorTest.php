<?php

use PHPUnit\Framework\TestCase;
use App\Services\validator;

class ValidatorTest extends TestCase
{
    public function testValidateData()
    {
        $this->expectNotToPerformAssertions();
        $validDeviceData = [
            'brand' => 'Apple',
            'device' => 'iPhone 15 Pro Max',
            'memory_count' => 256,
            'battery_condition' => 98,
            'is_new' => false,
            'is_working' => true,
            'case_condition' => 'Идеальное, без царапин и сколов',
            'screen_condition' => 'Оригинальный экран, наклеено гидрогелевая пленка',
            'working_description' => 'Все функции работают исправно, FaceID и TrueTone активны',
            'phone' => '+79991234567',
            'name' => 'Иван Иванов',
            'equipment' => 3,
            'is_repair' => false,
        ];
        validator::validateData($validDeviceData);
    }
    public function testValidateWithWrongData()
    {
        $this->expectException(App\Exceptions\ValidationException::class);
        $invalidDeviceData = [
            'brand' => 'Apple',
            'device' => 'iPhone 15 Pro Max',
            'memory_count' => 5064,
            'battery_condition' => 101,
            'is_new' => false,
            'is_working' => true,
            'case_condition' => 'Идеальное, без царапин и сколоввввввввввввввввввввввввввввввввввввввввввввввввввв',
            'screen_condition' => 'Оригинальный экран, наклеено гидрогелевая пленка' . 8,
            'working_description' => 'Все функции работают исправно, FaceID и TrueTone активны',
            'phone' => '+79991234567',
            'name' => 'Иван Иванов +7921773123',
            'equipment' => 'шнур',
            'is_repair' => false,
        ];
        validator::validateData($invalidDeviceData);
    }
}
