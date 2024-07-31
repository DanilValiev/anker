<?php

namespace App\Mocker\Process\Providers\EndpointProvider;

use App\Mocker\Exceptions\Variations\Endpoints\EndpointNotFoundException;
use App\Mocker\Process\Providers\ProviderInterface;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Entity\Mocker\Endpoint;

class EndpointProvider implements ProviderInterface
{
    /**
     * @throws EndpointNotFoundException
     */
    public function get(ApplicationRequest $request): Endpoint
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