<?php

namespace App\Controller\Api\Process;

use App\Mocker\Process\RequestProcessInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProcessController extends AbstractController
{
    public function __construct(
        private readonly RequestProcessInterface $requestProcess
    )
    {
    }

    #[Route('/process/{scope}/{endpoint}', methods: ['GET', 'POST', 'PUT', 'DELETE'])]
    public function process(Request $request, string $scope, string $endpoint): JsonResponse
    {
        $response = $this->requestProcess->process($request, ['scope' => $scope, 'endpoint' => $endpoint]);

        return new JsonResponse($response->getData(), $response->getStatusCode(), json: true);
    }
}