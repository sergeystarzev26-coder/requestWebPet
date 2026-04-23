<?php
namespace App\Dto;
readonly class adminDto{
    public function __construct(
        public string $action,
        public int $id,
        )
        {}
}
?>
