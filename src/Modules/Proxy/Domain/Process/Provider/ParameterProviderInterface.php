<?php

namespace App\Modules\Proxy\Domain\Process\Provider;

use App\Shared\Domain\Model\ApplicationCommand;

interface ParameterProviderInterface
{
    public function get(ApplicationCommand $request): array|string|null;
}