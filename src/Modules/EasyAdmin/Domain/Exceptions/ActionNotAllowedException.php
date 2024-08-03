<?php

namespace App\Modules\EasyAdmin\Domain\Exceptions;

use Exception;
use Throwable;

class ActionNotAllowedException extends Exception
{
    public function __construct(string $message = 'This action is prohibited', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}