<?php

namespace App\Modules\Mocker\Domain\Process\Provider;

use App\Shared\Domain\Model\ApplicationCommand;

interface ParametersProviderInterface
{
    public function get(ApplicationCommand $request): array;
}