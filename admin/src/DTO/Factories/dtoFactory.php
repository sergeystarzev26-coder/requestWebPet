<?php

namespace App\Dto\Factories;

use App\Dto\adminDto;
use App\Dto\loginDto;
use App\Dto\DtoInterface;
use App\Exceptions\inputErr;

class DtoFactory 
{

    public static function fromArray(array $data): DtoInterface 
    {
        if (isset($data['login'], $data['password'])) {
            return new loginDto(
                trim((string)$data['login']), 
                trim((string)$data['password'])
            );
        }
        
        if (isset($data['id'], $data['action'])) {
            return new adminDto(
                trim((string)$data['action']),
                (int)$data['id']
            );
        }
        
        throw new inputErr('unvalid input');
    }
}
