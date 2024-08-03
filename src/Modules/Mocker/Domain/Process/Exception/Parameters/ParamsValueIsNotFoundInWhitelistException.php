<?php

namespace App\Modules\Mocker\Domain\Process\Exceptions\Parameters;

use Exception;
use Throwable;

class ParamsValueIsNotFoundInWhitelistException extends Exception
{
    public function __construct(string $message = 'Params value is not found in whitelist', int $code = 419, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}