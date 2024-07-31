<?php

namespace App\Mocker\Exceptions\Variations\Parameters;

use Exception;
use Throwable;

class InvalidParamsTypeException extends Exception
{
    public function __construct(string $message = 'Required params not found', int $code = 418, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}