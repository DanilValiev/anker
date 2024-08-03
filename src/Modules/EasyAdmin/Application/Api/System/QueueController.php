<?php

namespace App\Modules\EasyAdmin\Application\Api\System;

use App\Modules\Scheduler\Infrastructure\Manager\EntityInQueueManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QueueController extends AbstractController
{
    public function __construct(
        private EntityInQueueManager $entityInQueueManager
    ) { }

    #[Route('queue/cancel', name: 'queue_cancel')]
    public function cancelJob(Request $request): Response
    {
        $this->entityInQueueManager->cancelJob($request->get('jobId'));

        return new RedirectResponse($request->get('referrer'));
    }

    #[Route('queue/detail', name: 'queue_detail')]
    public function renderQueuePage(Request $request): Response
    {
        $data = $this->entityInQueueManager->getFutureChanges($request->get('routeParams')['id']);

        if ($data == null) {
            return $this->render('Exceptions/schedulerEmptyTimeException.html.twig', [
                'message' => "Отложенных изменений не найдено. \nВозможно они уже были применены или их действительно не существовало.",
                'referrer' => $request->get('referrer')
            ]);
        }

        $changesArray = $this->entityInQueueManager->prepareChanges($data['args']);

        return $this->render('/Schedule/table.html.twig', [
            'changes' => $changesArray,
            'executeafter' => $data['executeafter'],
            'referrer' => $request->get('referrer'),
            'jobId' => $data['jobId']
        ]);
    }
}