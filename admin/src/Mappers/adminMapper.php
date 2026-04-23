<?php
namespace App\Mappers;
use app\Dto\adminDto;
use app\Exceptions\inputErr;
class adminMapper{
    public static function fromArray($data) : adminDto{
        if(!isset($data['action'], $data['id'])){
            error_log('missing required fields:action or id');
            throw new inputErr('Missing required fields: action or id');
        }
        return new adminDto(
            action:           (string)($data['action']),
            id:               (int)($data['id']),
        );
    }
}
?>
