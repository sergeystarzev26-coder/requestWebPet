<?php
namespace App\Services;
use App\Exceptions\HandlerException;
class requestHandler{

    //get JSON with device data from php input.also decodes data into an associative array
    public static function takeDataFromPost() {
        $rawData = file_get_contents('php://input');
        if (!json_validate($rawData)) throw new HandlerException('postData != Json');
        if(empty($rawData)){ throw new HandlerException('empty request');}
        $decodeData = json_decode($rawData, true);
        if($decodeData === null) throw new HandlerException('json_decodes returns NULL');
        $deviceData = $decodeData;
        return is_array($deviceData) ? $deviceData : throw new HandlerException('decode Data error');
    }
}
?>