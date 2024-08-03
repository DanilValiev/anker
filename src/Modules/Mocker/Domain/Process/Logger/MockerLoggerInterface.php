<?php

namespace App\Modules\Mocker\Domain\Process\Logger;

use App\Shared\Domain\Entity\Mocker\ProcessLog;
use App\Shared\Domain\Model\ApplicationCommand;

interface MockerLoggerInterface
{
    public function logMocker(ApplicationCommand $applicationCommand): ProcessLog;
}