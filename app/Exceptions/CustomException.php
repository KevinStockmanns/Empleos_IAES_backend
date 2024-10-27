<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $status;

    public function __construct($message, $status)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
