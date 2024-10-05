<?php

namespace App\Modules\Mocker\Domain\Process\Provider;

use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Model\ApplicationCommand;

interface EndpointProviderInterface
{
    public function get(ApplicationCommand $request): ?Endpoint;
}