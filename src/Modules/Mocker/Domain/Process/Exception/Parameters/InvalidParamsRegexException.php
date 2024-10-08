<?php

namespace App\Modules\Mocker\Domain\Process\Exceptions\Parameters;

use Exception;
use Throwable;

class InvalidParamsRegexException extends Exception
{
    public function __construct(string $message = 'Params structure invalid', int $code = 418, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}