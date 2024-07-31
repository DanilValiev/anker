<?php

namespace App\Mocker\Process\Providers\ScopesProvider;

use App\Mocker\Exceptions\Variations\Scopes\ScopeNotFoundException;
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

    /**
     * @throws ScopeNotFoundException
     */
    public function get(ApplicationRequest $request): ApiScope
    {
        $scope = $this->apiScopesRepository->findOneBy(['slug' => $request->getScopePath()]);

        if (!$scope instanceof ApiScope) {
            throw new ScopeNotFoundException();
        }

        return $scope;
    }
}