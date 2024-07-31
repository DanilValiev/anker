<?php

namespace App\Mocker\Exceptions\Variations\Endpoints;

use Exception;
use Throwable;

class EndpointNotFoundException extends Exception
{
    public function __construct(string $message = 'Endpoint not found', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}