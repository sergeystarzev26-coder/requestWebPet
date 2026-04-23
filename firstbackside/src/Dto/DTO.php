<?php
namespace App\Dto;
readonly class DTO{
    public function __construct(
        public string $brand,
        public string $name,
        public int    $memory,
        public bool   $isNew,
        public int    $batteryCondition,
        public string $caseCondition,
        public string $screenCondition,
        public bool   $isWorking,
        public string $workingDescription,
        public string $userPhone,
        public string $userName,
        public int    $equipment,
        public bool   $isRepair
        )    
        {}
}
?>
