<?php

namespace App\Modules\Proxy\Infrastructure\Process\Provider;

use App\Modules\Proxy\Domain\Process\Exception\ProxyNotFoundException;
use App\Modules\Proxy\Domain\Process\Provider\ProxyProviderInterface;
use App\Shared\Domain\Entity\Proxy\Proxy;
use App\Shared\Domain\Model\ApplicationCommand;
use App\Shared\Infrastructure\Doctrine\Repository\Proxy\ProxyRepository;

class ProxyProvider implements ProxyProviderInterface
{
    public function __construct(
        private readonly ProxyRepository $proxyRepository
    )
    {
    }

    /**
     * @param ApplicationCommand $request
     * @return Proxy[]
     */
    public function getFromEndpoint(ApplicationCommand $request): array
    {
        $endpointProxy = $request->getEndpoint()->getProxy()->filter(
            function (Proxy $endpointProxy) { return $endpointProxy->getActive(); }
        );

        $proxyArray = $endpointProxy->toArray();
        usort($proxyArray, function (Proxy $a, Proxy $b) {
            return $a->getWeight() >= $b->getWeight();
        });

        return $proxyArray;
    }

    public function getFromUrl(ApplicationCommand $request): ?Proxy
    {
        return $this->proxyRepository->findOneBy(['swappedUrl' => $request->getEndpoint(), 'active' => true]);
    }
}