<?php

namespace App\Modules\Proxy\Application\Api\Process;

use App\Modules\Proxy\Domain\Process\Exception\ProxyNotFoundException;
use App\Modules\Proxy\Domain\Proxy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProxyController extends AbstractController
{
    public function __construct(
        private readonly Proxy $proxy
    )
    {
    }

    /**
     * @throws ProxyNotFoundException
     */
    #[Route('/proxy/{endpoint}', methods: ['GET', 'POST', 'PUT', 'DELETE'])]
    public function process(Request $request, string $endpoint): JsonResponse
    {
        $applicationCommand = $this->proxy->process($request, ['endpoint' => $endpoint]);
        $response = $applicationCommand->getProxyResponse();

        return new JsonResponse($response->getResponse(), $response->getResponseCode(), $response->getReceivedHeaders(), json: true);
    }
}