<?php
namespace App\Mappers;
use App\Dto\DTO;
class Mapper{
    public static function fromArray($deviceData) : DTO{
        return new DTO(
            brand:              (string)($deviceData['brand'] ?? ''),
            name:               (string)($deviceData['device'] ?? ''),
            memory:             (int)($deviceData['memory_count'] ?? 0),
            isNew:              (bool)($deviceData['is_new'] ?? false),
            batteryCondition:   (int)($deviceData['battery_condition'] ?? 0),
            caseCondition:      (string)($deviceData['case_condition'] ?? ''),
            screenCondition:    (string)($deviceData['screen_condition'] ?? ''),
            isWorking:          (bool)($deviceData['is_working'] ?? false),
            workingDescription: (string)($deviceData['working_description'] ?? ''),
            userPhone:          (string)($deviceData['phone'] ?? ''),
            userName:           (string)($deviceData['name'] ?? ''),
            equipment:          (int)($deviceData['equipment'] ?? 0),
            isRepair:           (bool)($deviceData['is_repair'] ?? false),
        );
    }
}
?>
