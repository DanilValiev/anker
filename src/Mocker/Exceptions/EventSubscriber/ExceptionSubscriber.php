<?php

namespace App\Mocker\Exceptions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/mocker')) {
            $exception = $event->getThrowable();

            $response = new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);

            $event->setResponse($response);
        }
    }
}