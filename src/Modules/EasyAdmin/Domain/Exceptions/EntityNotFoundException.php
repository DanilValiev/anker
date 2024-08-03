<?php

namespace App\Modules\EasyAdmin\Domain\Exceptions;

use Exception;
use Throwable;

class EntityNotFoundException extends Exception
{
    public function __construct(string $message = 'Entity not found', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}