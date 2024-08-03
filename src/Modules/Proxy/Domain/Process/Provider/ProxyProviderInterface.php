<?php

namespace App\Modules\Proxy\Domain\Process\Provider;

use App\Shared\Domain\Entity\Proxy\Proxy;
use App\Shared\Domain\Model\ApplicationCommand;

interface ProxyProviderInterface
{
    /**
     * @param ApplicationCommand $request
     *
     * @return Proxy[]
     */
    public function getFromEndpoint(ApplicationCommand $request): array;

    public function getFromUrl(ApplicationCommand $request): ?Proxy;
}