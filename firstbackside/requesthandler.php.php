<?php
class requestHandler{
    public static function takeDataFromPost() {
        $rawData = file_get_contents('php://input');
        if (!json_validate($rawData)) throw new Exception('postData != Json');
        if(empty($rawData)){ throw new Exception('empty request');}
        $decodeData = json_decode($rawData, true);
        if($decodeData === null) throw new Exception('json_decodes returns NULL');
        $deviceData = $decodeData;
        return is_array($deviceData) ? $deviceData : throw new Exception('decode Data error');
    }
}
?>