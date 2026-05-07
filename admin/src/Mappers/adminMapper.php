<?php
namespace App\Mappers;
use app\Dto\adminDto;
use app\Exceptions\inputErr;
class adminMapper{
    public static function fromArray($data) : adminDto{
        if(empty($data['action']) || empty($data['id']) || !is_int($data['id'])){
            throw new inputErr('Missing required fields: action or id');
        }
        return new adminDto(
            action:           (string)($data['action']),
            id:               (int)($data['id']),
        );
    }
}
?>
