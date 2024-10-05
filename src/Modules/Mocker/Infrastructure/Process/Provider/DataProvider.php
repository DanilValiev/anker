<?php

namespace App\Modules\Mocker\Infrastructure\Process\Provider;

use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointDataNotFoundException;
use App\Modules\Mocker\Domain\Process\Provider\DataProviderInterface;
use App\Modules\Mocker\Infrastructure\ApplicationExpression\ApplicationExpression;
use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointData;
use App\Shared\Domain\Model\ApplicationCommand;

class DataProvider implements DataProviderInterface
{
    public function __construct(
        private readonly ApplicationExpression $applicationExpression
    )
    {
    }

    /**
     * @throws EndpointDataNotFoundException
     */
    public function get(ApplicationCommand $request): ?EndpointData
    {
        $responseData = $request->getEndpoint()->getData()->filter(
            function(EndpointData $data) use ($request) {
                if (!$data->isActive()) {
                    return false;
                }

                if ($data->getExpression()) {
                    return $this->applicationExpression->process($data->getExpression(), $request->getParameters());
                }

                return true;
            })->first();

        if (!$responseData instanceof EndpointData) {
            throw new EndpointDataNotFoundException();
        }

        return $responseData;
    }
}