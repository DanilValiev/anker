<?php

namespace App\Modules\EasyAdmin\Application\Api\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    #[Route('app/status', methods: ['GET'])]
    public function getStatus(Request $request): JsonResponse
    {
        $status = [
            'env' => $_ENV['APP_ENV'],
            'git_branch' => $_ENV['GIT_BRANCH'] ?? null,
            'git_commit' => $_ENV['GIT_COMMIT'] ?? null,
        ];

        return new JsonResponse($status);
    }
}