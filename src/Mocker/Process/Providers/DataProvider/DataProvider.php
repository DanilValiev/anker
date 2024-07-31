<?php

namespace App\Mocker\Process\Providers\DataProvider;

use App\Mocker\ApplicationExpression\ApplicationExpressionInterface;
use App\Mocker\Exceptions\Variations\Endpoints\EndpointDataNotFoundException;
use App\Mocker\Process\Providers\ProviderInterface;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Entity\Mocker\EndpointData;

class DataProvider implements ProviderInterface
{
    public function __construct(
        private readonly ApplicationExpressionInterface $applicationExpression
    )
    {
    }

    /**
     * @throws EndpointDataNotFoundException
     */
    public function get(ApplicationRequest $request): EndpointData
    {
        $responseData = $request->getEndpoint()->getData()->filter(
            function(EndpointData $data) use ($request) {
                if (!$data->isActive()) {
                    return false;
                }

                if ($data->getExpression()) {
                    return $this->applicationExpression->process($data->getExpression(), $request->getParams());
                }

                return true;
            })->first();

        if (!$responseData instanceof EndpointData) {
            throw new EndpointDataNotFoundException();
        }

        return $responseData;
    }
}