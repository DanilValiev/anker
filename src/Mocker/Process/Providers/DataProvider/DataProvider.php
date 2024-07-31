<?php

namespace App\Mocker\Process\Providers\DataProvider;

use App\Mocker\ApplicationExpression\ApplicationExpressionInterface;
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

    public function get(ApplicationRequest $request): EndpointData
    {
        return $request->getEndpoint()->getData()->filter(
            function($data) use ($request) {
                if (!$data->isActive()) {
                    return false;
                }

                if ($data->getExpression()) {
                    return $this->applicationExpression->process($data->getExpression(), $request->getParams());
                }

                return true;
            })->first();
    }
}