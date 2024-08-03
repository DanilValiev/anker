<?php

namespace App\Modules\Mocker\Domain\Process\Provider;

use App\Shared\Domain\Entity\Mocker\ApiScope;
use App\Shared\Domain\Model\ApplicationCommand;

interface ScopesProviderInterface
{
    public function get(ApplicationCommand $request): ?ApiScope;
}