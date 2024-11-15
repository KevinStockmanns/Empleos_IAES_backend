<?php

namespace App\DTO;

class ErrorDTO{
    public $message;
    public $errors;

    public function __construct(string $message, $errors=null) {
        $this->message = $message;
        $this->errors = $errors;
    }
}