<?php

namespace App\Mocker\Exceptions\Variations\Scopes;

use Exception;
use Throwable;

class ScopeNotFoundException extends Exception
{
    public function __construct(string $message = 'Scope not found', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}