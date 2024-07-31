<?php

namespace App\Mocker\Exceptions\Parameters;

use Exception;
use Throwable;

class ParamsNotFoundException extends Exception
{
    public function __construct(string $message = 'Params type invalid', int $code = 417, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}