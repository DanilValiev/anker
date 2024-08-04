<?php

namespace App\Shared\Infrastructure\Logger\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\LogRecord;

class FilterHandler extends StreamHandler
{
    public function __construct(private readonly array $excludedMessages, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct('php://stdout', $level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        foreach ($this->excludedMessages as $message) {
            if (str_contains($record->message, $message)) {
                return;
            }
        }

        parent::write($record);
    }
}