<?php

namespace App\Modules\EasyAdmin\Application\Api\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exceptions/')]
class CustomUiExceptionController extends AbstractController
{
    public function schedulerEmptyTimeException(string $message, string $referrer): Response
    {
        return $this->render('Exceptions/schedulerEmptyTimeException.html.twig', [
           'message' => $message,
           'referrer' => $referrer
        ]);
    }
}