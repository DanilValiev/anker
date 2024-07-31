<?php

namespace App\Mocker\Process\Providers\ScopesProvider;

use App\Mocker\Process\Providers\ProviderInterface;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Entity\Mocker\ApiScope;
use App\Shared\Doctrine\Repository\Mocker\ApiScopesRepository;

class ScopesProvider implements ProviderInterface
{
    public function __construct(
        private readonly ApiScopesRepository $apiScopesRepository
    )
    {
    }

    public function get(ApplicationRequest $request): ApiScope
    {
        return $this->apiScopesRepository->findOneBy(['slug' => $request->getScopePath()]);
    }
}