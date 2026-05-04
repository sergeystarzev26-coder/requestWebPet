<?php
namespace App\Dto;
class adminDto{
    public function __construct(
        public string $action,
        public int $id,
        )
        {}
}
?>
