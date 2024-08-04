<?php

namespace App\Modules\Proxy\Domain\Process\Logger;

use App\Shared\Domain\Entity\Proxy\ProxyLog;
use App\Shared\Domain\Model\ApplicationCommand;

interface ProxyLoggerInterface
{
    public function logProxy(ApplicationCommand $applicationCommand): ProxyLog;
}