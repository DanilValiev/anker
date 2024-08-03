<?php

namespace App\Modules\Proxy\Domain\Process\Exception;

use Exception;
use Throwable;

class ProxyNotFoundException extends Exception
{
    public function __construct(string $message = 'Proxy not found', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}