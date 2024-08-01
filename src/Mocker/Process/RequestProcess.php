<?php

namespace App\Mocker\Process;

use App\Mocker\Exceptions\Variations\Endpoints\EndpointDataNotFoundException;
use App\Mocker\Exceptions\Variations\Endpoints\EndpointNotFoundException;
use App\Mocker\Exceptions\Variations\Parameters\InvalidParamsRegexException;
use App\Mocker\Exceptions\Variations\Parameters\InvalidParamsTypeException;
use App\Mocker\Exceptions\Variations\Parameters\ParamsNotFoundException;
use App\Mocker\Exceptions\Variations\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Mocker\Exceptions\Variations\Scopes\ScopeNotFoundException;
use App\Mocker\Logger\ApplicationLogger;
use App\Mocker\Process\Providers\DataProvider\DataProvider;
use App\Mocker\Process\Providers\EndpointProvider\EndpointProvider;
use App\Mocker\Process\Providers\ParametersProvider\ParametersProvider;
use App\Mocker\Process\Providers\ScopesProvider\ScopesProvider;
use App\Mocker\Process\Request\Factory\ApplicationRequestFactoryInterface;
use App\Shared\Doctrine\Entity\Mocker\EndpointData;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestProcess implements RequestProcessInterface
{
    public function __construct(
        private readonly ApplicationRequestFactoryInterface $applicationRequestFactory,
        private readonly EndpointProvider $endpointProvider,
        private readonly ScopesProvider $scopesProvider,
        private readonly ParametersProvider $parametersProvider,
        private readonly DataProvider $dataProvider,
        private readonly LoggerInterface $logger,
        private readonly ApplicationLogger $applicationLogger
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
     */
    public function process(Request $request, array $urlDetails): ?EndpointData
    {
        $this->logger->info('Request process started!', ['url' => $request->getBaseUrl()]);
        $applicationRequest = $this->applicationRequestFactory->create($request, $urlDetails);

        try {

            $applicationRequest
                ->setApiScope($this->scopesProvider->get($applicationRequest))
                ->setEndpoint($this->endpointProvider->get($applicationRequest))
                ->setParams($this->parametersProvider->get($applicationRequest))
                ->setEndpointData($this->dataProvider->get($applicationRequest))
            ;
        } catch (\Exception $exception) {
            $applicationRequest->setError($exception->getMessage())->setErrorCode($exception->getCode());
            $this->logger->error('Request process error!', ['ex' => $exception, 'code' => $exception->getCode()]);

            throw $exception;
        } finally {
            $this->logger->info('Request process finished!', [
                    'url' => $request->getBaseUrl(),
                    'params' => $applicationRequest->getParams()
            ]);

            $this->applicationLogger->log($applicationRequest);
        }

        sleep($applicationRequest->getEndpoint()->getSleepTime());

        return $applicationRequest->getEndpointData();
    }
}