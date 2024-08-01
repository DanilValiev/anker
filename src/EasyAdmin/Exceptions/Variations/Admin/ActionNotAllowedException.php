<?php

namespace App\EasyAdmin\Exceptions\Variations\Admin;

use Exception;
use Throwable;

class ActionNotAllowedException extends Exception
{
    public function __construct(string $message = 'This action is prohibited', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}