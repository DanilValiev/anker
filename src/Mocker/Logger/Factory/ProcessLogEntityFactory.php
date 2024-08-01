<?php

namespace App\Mocker\Logger\Factory;

use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Entity\Mocker\ProcessLog;

class ProcessLogEntityFactory
{
    public static function createFromApplicationRequest(ApplicationRequest $applicationRequest): ProcessLog
    {
        return (new ProcessLog())
            ->setMethod($applicationRequest->getMethod())
            ->setIncomingHeaders($applicationRequest->getUserHeaders())
            ->setIncomingParams($applicationRequest->getUserParams())
            ->setUserIps($applicationRequest->getUserIp())
            ->setScope($applicationRequest->getApiScope())
            ->setEndpoint($applicationRequest->getEndpoint())
            ->setResponse(
                $applicationRequest->getError() ?? $applicationRequest->getEndpointData()->getData()
            )
            ->setResponseCode(
                $applicationRequest->getErrorCode() ?? $applicationRequest->getEndpointData()->getStatusCode()
            );
    }
}