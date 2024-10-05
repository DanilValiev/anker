<?php

namespace App\Modules\Mocker\Application\Api\Process;

use App\Modules\Mocker\Domain\Mocker;
use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointDataNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Endpoints\EndpointNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsRegexException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsTypeException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Modules\Mocker\Domain\Process\Exceptions\Scopes\ScopeNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProcessController extends AbstractController
{
    public function __construct(
        private readonly Mocker $mocker
    )
    {
    }

    /**
     * @throws EndpointNotFoundException
     * @throws InvalidParamsTypeException
     * @throws ParamsValueIsNotFoundInWhitelistException
     * @throws InvalidParamsRegexException
     * @throws ParamsNotFoundException
     * @throws ScopeNotFoundException
     * @throws EndpointDataNotFoundException
     */
    #[Route('/{scope}/{endpoint}', methods: ['GET', 'POST', 'PUT', 'DELETE'])]
    public function process(Request $request, string $scope, string $endpoint): JsonResponse
    {
        $applicationCommand = $this->mocker->process($request, ['scope' => $scope, 'endpoint' => $endpoint]);

        if ($applicationCommand->getProxyResponse()) {
            $response = $applicationCommand->getProxyResponse();

            return new JsonResponse($response->getResponse(), $response->getResponseCode(), json: true);
        } else if ($applicationCommand->getEndpointData()) {
            $response = $applicationCommand->getEndpointData();

            return new JsonResponse($response->getData(), $response->getStatusCode(), json: true);
        }

        throw new EndpointNotFoundException();
    }
}