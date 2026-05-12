<?php

use App\Dto\adminDto;
use PHPUnit\Framework\TestCase;
use App\Mappers\adminMapper;

class mapperTest extends TestCase
{
    public function testMapper()
    {
        $data = ['action' => 'pause', 'id' => 42];
        $expectedDto = new adminDto(
            action: $data['action'],
            id: $data['id']
        );
        $result = adminMapper::fromArray($data);
        $this->assertEquals($expectedDto, $result);
    }
    public function testMapperExceptionEmptyString()
    {
        $data = ['action' => '', 'id' => ''];
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('Missing required fields: action or id');
        adminMapper::fromArray($data);
    }
    public function testMapperExceptionStringId()
    {
        $data = ['action' => 'pause', 'id' => '67'];
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('Missing required fields: action or id');
        adminMapper::fromArray($data);
    }
    public function testMapperExceptionStringIdEmpty()
    {
        $data = ['action' => 'pause', 'id' => ''];
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('Missing required fields: action or id');
        adminMapper::fromArray($data);
    }
    public function testMapperExceptionStringActionEmpty()
    {
        $data = ['action' => '', 'id' => 42];
        $this->expectException(\App\Exceptions\inputErr::class);
        $this->expectExceptionMessage('Missing required fields: action or id');
        adminMapper::fromArray($data);
    }
}
