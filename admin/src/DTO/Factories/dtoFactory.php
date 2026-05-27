<?php
namespace App\Dto\Factories;

use App\Dto\adminDto;
use App\Dto\loginDto;
use App\Dto\DtoInterface;
use App\Exceptions\inputErr;

class DtoFactory {
    // Указываем строгий возвращаемый тип интерфейса
    public static function fromArray(array $data): DtoInterface {
        
        // Проверка для логина
        if (isset($data['login'], $data['password'])) {
            return new loginDto(
                trim((string)$data['login']), 
                trim((string)$data['password'])
            );
        }
        
        // Проверка для админки
        if (isset($data['id'], $data['action'])) {
            return new adminDto(
                (int)$data['id'], 
                trim((string)$data['action'])
            );
        }
        
        throw new inputErr('unvalid input');
    }
}
