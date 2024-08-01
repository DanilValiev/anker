<?php

namespace App\Mocker\Logger;

use App\Mocker\Logger\Factory\ProcessLogEntityFactory;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Shared\Doctrine\Repository\Mocker\ProcessLogRepository;

class ApplicationLogger
{
    public function __construct(
        private readonly ProcessLogRepository $processLogRepository
    )
    {
    }

    public function log(ApplicationRequest $applicationRequest): void
    {
        $logEntity = ProcessLogEntityFactory::createFromApplicationRequest($applicationRequest);
        $this->processLogRepository->create($logEntity);
    }
}