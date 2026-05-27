<?php
namespace App\Dto;
class adminDto implements DtoInterface{
    public function __construct(
        public string $action,
        public int $id,
        )
        {}
}
?>
