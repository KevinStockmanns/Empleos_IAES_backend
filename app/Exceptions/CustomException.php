<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $status;
    private string $field;

    public function __construct($message, $status, string $field = 'error')
    {
        parent::__construct($message);
        $this->status = $status;
        $this->field = $field;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function getField(): string
    {
        return $this->field;
    }
}
