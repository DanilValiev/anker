<?php

namespace App\Modules\Mocker\Domain\Process\Exceptions\Expressions;

use Exception;
use Throwable;

class InvalidExpressionStructureException extends Exception
{
    public function __construct(string $message = 'Invalid condition structure', int $code = 420, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}