<?php

namespace App\Modules\Proxy\Domain\Process;

use App\Modules\Proxy\Domain\Model\ProxyResponse;
use App\Shared\Domain\Model\ApplicationCommand;

interface RequestSenderInterface
{
    public function send(ApplicationCommand $request): ProxyResponse;
}