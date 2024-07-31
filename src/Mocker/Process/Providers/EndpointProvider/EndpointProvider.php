<?php

namespace App\Mocker\Process\Providers\EndpointProvider;

use App\Mocker\Process\Providers\ProviderInterface;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Entity\Mocker\Endpoint;

class EndpointProvider implements ProviderInterface
{
    public function get(ApplicationRequest $request): Endpoint
    {
        return $request->getApiScope()->getEndpoints()->filter(
            function(Endpoint $endpoint) use ($request) {
                return $endpoint->getSlug() == $request->getEndpointPath() && $endpoint->isActive() && ($endpoint->getMethods() == 'ANY' || $request->getMethod() == $endpoint->getMethods());
        })->first();
    }
}