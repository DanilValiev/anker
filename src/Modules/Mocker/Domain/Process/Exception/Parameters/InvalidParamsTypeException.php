<?php

namespace App\Modules\Mocker\Domain\Process\Exceptions\Parameters;

use Exception;
use Throwable;

class InvalidParamsTypeException extends Exception
{
    public function __construct(string $message = 'Required parameters not found', int $code = 418, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}