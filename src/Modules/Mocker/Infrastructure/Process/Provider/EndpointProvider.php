<?php

namespace App\Modules\Mocker\Infrastructure\Process\Provider;

use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointNotFoundException;
use App\Modules\Mocker\Domain\Process\Provider\EndpointProviderInterface;
use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Model\ApplicationCommand;

class EndpointProvider implements EndpointProviderInterface
{
    /**
     * @throws EndpointNotFoundException
     */
    public function get(ApplicationCommand $request): ?Endpoint
    {
        $endpoint = $request->getApiScope()->getEndpoints()->filter(
            function(Endpoint $endpoint) use ($request) {
                return $endpoint->getSlug() == $request->getEndpointPath() && $endpoint->isActive() && ($endpoint->getMethods() == 'ANY' || $request->getMethod() == $endpoint->getMethods());
        })->first();

        if (!$endpoint instanceof Endpoint) {
            throw new EndpointNotFoundException();
        }

        return $endpoint;
    }
}