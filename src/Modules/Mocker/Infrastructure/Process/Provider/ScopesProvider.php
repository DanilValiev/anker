<?php

namespace App\Modules\Mocker\Infrastructure\Process\Provider;

use App\Modules\Mocker\Domain\Process\Exceptions\Scopes\ScopeNotFoundException;
use App\Modules\Mocker\Domain\Process\Provider\ScopesProviderInterface;
use App\Shared\Domain\Entity\Mocker\ApiScope;
use App\Shared\Domain\Model\ApplicationCommand;
use App\Shared\Infrastructure\Doctrine\Repository\Mocker\ApiScopesRepository;

class ScopesProvider implements ScopesProviderInterface
{
    public function __construct(
        private readonly ApiScopesRepository $apiScopesRepository
    )
    {
    }

    /**
     * @throws ScopeNotFoundException
     */
    public function get(ApplicationCommand $request): ApiScope
    {
        $scope = $this->apiScopesRepository->findOneBy(['slug' => $request->getScopePath()]);

        if (!$scope instanceof ApiScope) {
            throw new ScopeNotFoundException();
        }

        return $scope;
    }
}