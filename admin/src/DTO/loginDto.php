<?php
namespace App\Dto;
class loginDto implements DtoInterface{
    public function __construct(
        public string $login,
        public string $password,
        )
        {}
}
?>
