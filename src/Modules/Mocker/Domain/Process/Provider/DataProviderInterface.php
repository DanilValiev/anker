<?php

namespace App\Modules\Mocker\Domain\Process\Provider;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointData;
use App\Shared\Domain\Model\ApplicationCommand;

interface DataProviderInterface
{
    public function get(ApplicationCommand $request): ?EndpointData;
}