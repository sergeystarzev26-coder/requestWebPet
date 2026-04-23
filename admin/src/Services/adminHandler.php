<?php
namespace App\Services;

use App\Exceptions\inputErr;
use Exception;
class adminHandler{
    public static function takeDataFromPost() {
        $rawData = file_get_contents('php://input');
        if (!json_validate($rawData)) {error_log('postdata != json'); throw new inputErr('postData != Json');}
        if(empty($rawData)){error_log('empty rawdata'); throw new inputErr('empty request');}
        $decodeData = json_decode($rawData, true);
        if($decodeData === null){error_log('json decodes returns null'); throw new inputErr('json_decodes returns NULL');}
        $deviceData = $decodeData;
        if (!is_array($deviceData)) {
            error_log('decode DATA err');
            throw new Exception('decode Data error');
            }
        return $deviceData;
    }
}
?>