<?php

namespace App\Modules\Proxy\Domain;

use App\Modules\Proxy\Domain\Process\Logger\ProxyLoggerInterface;
use App\Modules\Proxy\Domain\Model\ProxyResponse;
use App\Modules\Proxy\Domain\Process\Exception\ProxyNotFoundException;
use App\Modules\Proxy\Domain\Process\Provider\ParameterProviderInterface;
use App\Modules\Proxy\Domain\Process\Provider\ProxyProviderInterface;
use App\Modules\Proxy\Domain\Process\RequestSenderInterface;
use App\Shared\Domain\Model\ApplicationCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class Proxy
{
    private int $nextProxyCount = 0;

    public function __construct(
        private readonly ProxyProviderInterface $proxyProvider,
        private readonly ParameterProviderInterface $parameterProvider,
        private readonly RequestSenderInterface $requestSender,
        private readonly ProxyLoggerInterface $proxyLogger,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @throws ProxyNotFoundException
     */
    public function process(Request $request, array $urlDetails): ApplicationCommand
    {
        $this->logger->info('Proxy request process started!', ['proxyUrl' => $request->getBaseUrl()]);
        $applicationCommand = ApplicationCommand::class->create($request, $urlDetails);

        $this->proxyProvider->getFromUrl($applicationCommand);
        $this->completeApplicationRequestProxyData($applicationCommand);
        $proxy = $applicationCommand->getProxy();

        if (!$proxy) {
            throw new ProxyNotFoundException("Proxy {$applicationCommand->getEndpoint()} not found");
        }

        for (; $this->nextProxyCount > 0; $this->nextProxyCount--) {
            $proxy = $proxy->getNextProxy();
        }

        try {
            $response = $this->requestSender->send($applicationCommand);
        } catch (\Exception $exception) {
            $response = ProxyResponse::createFromException($exception);
        }

        $applicationCommand->setProxyResponse($response);
        $this->proxyLogger->logProxy($applicationCommand);

        if ($this->nextProxyCount == 0 && $proxy->getNextProxy()) {
            $this->nextProxyCount++;

            $applicationCommand->setProxy($proxy->getNextProxy());
            $this->process($request, $urlDetails);
        }

        $this->logger->info('Request process finish!', ['proxyUrl' => $request->getBaseUrl()]);
        return $applicationCommand;
    }

    public function processFromEndpoint(ApplicationCommand $applicationCommand): ?ApplicationCommand
    {
        $this->logger->info('Proxy request process started!', ['proxyUrl' => $applicationCommand->getProxyUrl()]);
        $proxyCollection = $this->proxyProvider->getFromEndpoint($applicationCommand);

        /** @var Proxy $proxy */
        foreach ($proxyCollection as $proxy) {
            $applicationCommand->setProxy($proxy);
            $this->completeApplicationRequestProxyData($applicationCommand);

            try {
                $response = $this->requestSender->send($applicationCommand);
            } catch (\Exception $exception) {
                $response = ProxyResponse::createFromException($exception);
            }

            $applicationCommand->setProxyResponse($response);
            $proxyLog = $this->proxyLogger->logProxy($applicationCommand);
            $applicationCommand->getEndpoint()->addProxyLogs($proxyLog);

            if ($response->getResponseCode() == $applicationCommand->getProxyRequiredResponseCode()) {
                break ;
            }

            if ($proxy->getNextProxy()) {
                $applicationCommand->setProxy($proxy->getNextProxy());
                $this->processFromEndpoint($applicationCommand);
            }
        }

        $this->entityManager->flush();

        $this->logger->info('Request process finish!', ['proxyUrl' => $applicationCommand->getProxyUrl()]);
        return $applicationCommand;
    }

    private function completeApplicationRequestProxyData(ApplicationCommand $request): void
    {
        $proxy = $request->getProxy();

        $request
            ->setProxyUrl($proxy?->getUrl())
            ->setProxyParametersBagType($proxy?->getParametersBagType())
            ->setPreparedParams($this->parameterProvider->get($request))
            ->setProxyAdditionalHeaders($proxy?->getAdditionalHeaders())
        ;
    }
}