<?php

use App\Dto\Dto;
use PHPUnit\Framework\TestCase;
use App\Mappers\Mapper;

class mapperTest extends TestCase
{
    public function testMapper()
    {
        $fullDeviceData = [
            'brand' => 'Apple',
            'device' => 'iPhone 15 Pro',
            'memory_count' => 128,
            'is_new' => false,
            'battery_condition' => 89,
            'case_condition' => 'Хорошее, есть мелкие царапины',
            'screen_condition' => 'Идеальное, под пленкой',
            'is_working' => true,
            'working_description' => 'Все датчики и компоненты исправны',
            'phone' => '+79998887766',
            'name' => 'Алексей Петров',
            'equipment' => 2,
            'is_repair' => true,
        ];

        $expectedFullDto = new DTO(
            brand: 'Apple',
            name: 'iPhone 15 Pro',
            memory: 128,
            isNew: false,
            batteryCondition: 89,
            caseCondition: 'Хорошее, есть мелкие царапины',
            screenCondition: 'Идеальное, под пленкой',
            isWorking: true,
            workingDescription: 'Все датчики и компоненты исправны',
            userPhone: '+79998887766',
            userName: 'Алексей Петров',
            equipment: 2,
            isRepair: true
        );
        $result = Mapper::fromArray($fullDeviceData);
        $this->assertEquals($expectedFullDto, $result);
    }
    public function testEmptyData()
    {
        $fakedata = [];
        $expectedDefaultDto = new DTO(
            brand: '',
            name: '',
            memory: 0,
            isNew: false,
            batteryCondition: 0,
            caseCondition: '',
            screenCondition: '',
            isWorking: false,
            workingDescription: '',
            userPhone: '',
            userName: '',
            equipment: 0,
            isRepair: false
        );
        $result = Mapper::fromArray($fakedata);
        $this->assertEquals($expectedDefaultDto, $result);
    }
}
