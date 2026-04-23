<?php
namespace App\Services;
use App\Exceptions\ValidationException;

class validator{

public static function validateData(array $deviceData) : void {

        $brand = $deviceData['brand'] ?? '';
        if (!is_string($brand) || empty($brand) || mb_strlen($brand) > 16) {
            throw new ValidationException('Incorrect deviceBrand');
        }

        $name = $deviceData['device'] ?? '';
        if (!is_string($name) || empty($name) || mb_strlen($name) > 32) {
            throw new ValidationException('Incorrect deviceName');
        }


        $memory = $deviceData['memory_count'] ?? null;
        if (!is_numeric($memory) || $memory <= 0 || $memory > 4096) {
            throw new ValidationException('Incorrect deviceMemory');
        }

        $battery = $deviceData['battery_condition'] ?? null;
        if (!is_numeric($battery) || $battery < 0 || $battery > 100) {
            throw new ValidationException('Incorrect batteryCondition');
        }

        if (!isset($deviceData['is_new']) || !is_bool($deviceData['is_new'])) {
            throw new ValidationException('Incorrect isNew');
        }

        if (!isset($deviceData['is_working']) || !is_bool($deviceData['is_working'])) {
            throw new ValidationException('Incorrect isWorking');
        }

        $case = $deviceData['case_condition'] ?? '';
        if (!is_string($case) || empty($case) || mb_strlen($case) > 256) {
            throw new ValidationException('Incorrect caseCondition');
        }

        $screen = $deviceData['screen_condition'] ?? '';
        if (!is_string($screen) || empty($screen) || mb_strlen($screen) > 256) {
            throw new ValidationException('Incorrect screenCondition');
        }

        $workDesc = $deviceData['working_description'] ?? '';
        if (!is_string($workDesc) || mb_strlen($workDesc) > 256) {
            throw new ValidationException('Incorrect workingDescription');
        }

        $phone = $deviceData['phone'] ?? '';
        if (!is_string($phone) || empty($phone) || mb_strlen($phone) > 15) {
            throw new ValidationException('Incorrect userPhone');
        }

        $userName = $deviceData['name'] ?? '';
        if (!is_string($userName) || empty($userName) || mb_strlen($userName) > 20) {
            throw new ValidationException('Incorrect userName');
        }

        $equipment = $deviceData['equipment'] ?? null;
        if (!is_numeric($equipment) || $equipment < 0 || $equipment > 10) {
            throw new ValidationException('Incorrect equipment');
        }

        if (!isset($deviceData['is_repair']) || !is_bool($deviceData['is_repair'])) {
            throw new ValidationException('Incorrect isRepair');
}   
}
}
?>
