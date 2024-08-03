<?php

namespace App\Modules\Mocker\Infrastructure\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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

        if (str_starts_with($request->getPathInfo(), '/proxy')) {
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