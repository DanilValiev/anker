<?php

namespace App\Shared\Infrastructure\Logger;

use App\Modules\Mocker\Domain\Process\Logger\MockerLoggerInterface;
use App\Modules\Proxy\Domain\Process\Logger\ProxyLoggerInterface;
use App\Shared\Domain\Entity\Mocker\ProcessLog;
use App\Shared\Domain\Entity\Proxy\ProxyLog;
use App\Shared\Domain\Model\ApplicationCommand;
use App\Shared\Infrastructure\Doctrine\Repository\Mocker\ProcessLogRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Proxy\ProxyLogRepository;

class ApplicationLogger implements ProxyLoggerInterface, MockerLoggerInterface
{
    public function __construct(
        private readonly ProcessLogRepository $processLogRepository,
        private readonly ProxyLogRepository $proxyLogRepository,
    )
    {
    }

    public function logMocker(ApplicationCommand $applicationCommand): ProcessLog
    {
        $logEntity = ProcessLog::createFromApplicationRequest($applicationCommand);
        $this->processLogRepository->create($logEntity);

        return $logEntity;
    }

    public function logProxy(ApplicationCommand $applicationCommand): ProxyLog
    {
        $logEntity = ProxyLog::createFromApplicationRequest($applicationCommand);
        $this->proxyLogRepository->create($logEntity);

        return $logEntity;
    }
}