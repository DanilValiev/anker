<?php

namespace App\Modules\Mocker\Domain;

use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointDataNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsRegexException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsTypeException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Modules\Mocker\Domain\Process\Exceptions\Scopes\ScopeNotFoundException;
use App\Modules\Mocker\Domain\Process\Logger\MockerLoggerInterface;
use App\Modules\Mocker\Domain\Process\Provider\DataProviderInterface;
use App\Modules\Mocker\Domain\Process\Provider\EndpointProviderInterface;
use App\Modules\Mocker\Domain\Process\Provider\ParametersProviderInterface;
use App\Modules\Mocker\Domain\Process\Provider\ScopesProviderInterface;
use App\Modules\Proxy\Domain\Proxy;
use App\Shared\Domain\Model\ApplicationCommand;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class Mocker
{
    public function __construct(
        private readonly EndpointProviderInterface   $endpointProvider,
        private readonly ScopesProviderInterface     $scopesProvider,
        private readonly ParametersProviderInterface $parametersProvider,
        private readonly DataProviderInterface       $dataProvider,
        private readonly MockerLoggerInterface       $applicationLogger,
        private readonly LoggerInterface             $logger,
        private readonly Proxy                       $proxy
    )
    {
    }

    /**
     * @throws InvalidParamsTypeException
     * @throws ScopeNotFoundException
     * @throws ParamsValueIsNotFoundInWhitelistException
     * @throws EndpointNotFoundException
     * @throws ParamsNotFoundException
     * @throws InvalidParamsRegexException
     * @throws EndpointDataNotFoundException
     * @throws Exception
     */
    public function process(Request $request, array $urlDetails): ?ApplicationCommand
    {
        $this->logger->info('Request process started!', ['url' => $request->getBaseUrl()]);
        $applicationCommand = ApplicationCommand::create($request, $urlDetails);

        try {
            $applicationCommand
                ->setApiScope($this->scopesProvider->get($applicationCommand))
                ->setEndpoint($this->endpointProvider->get($applicationCommand))
                ->setParameters($this->parametersProvider->get($applicationCommand))
            ;

            if ($applicationCommand->getEndpoint()->getProxy()->count() >= 1) {
                $this->proxy->processFromEndpoint($applicationCommand);
            }

            if ($applicationCommand->getProxyResponse()?->getResponseCode() != $applicationCommand->getProxyRequiredResponseCode()) {
                $applicationCommand->setEndpointData($this->dataProvider->get($applicationCommand));
            }
        } catch (Exception $exception) {
            $this->logger->error('Request process error!', [
                'ex' => $exception,
                'code' => $exception->getCode()
            ]);

            $applicationCommand
                ->setError("{\"success\": false, \"message\": \"{$exception->getMessage()}\", \"code\": {$exception->getCode()}}")
                ->setErrorCode($exception->getCode());

            throw $exception;
        } finally {
            $this->logger->info('Request process finished!', [
                    'url' => $request->getBaseUrl(),
                    'parameters' => $applicationCommand->getParameters()
            ]);

            $this->applicationLogger->logMocker($applicationCommand);
        }

        sleep($applicationCommand->getEndpoint()->getSleepTime());

        return $applicationCommand;
    }
}