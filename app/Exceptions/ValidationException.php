<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(string $message, $errorCode = HTTP_STATUS_UNPROCESS, Exception $old = null)
    {
        parent::__construct($message, $errorCode, $old);
    }
}
