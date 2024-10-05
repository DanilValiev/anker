<?php

namespace App\Shared\Infrastructure\ExceptionSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
        if (isset($_GET['debug']) && $_GET['debug']) {
            return;
        }

        $exception = $event->getThrowable();

        // Формируем тело JSON-ответа
        $data = [
            'error' => true,
            'message' => $exception->getMessage(),
        ];

        // Если это HTTP исключение, можем добавить статус код
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            // Для всех других исключений, устанавливаем код 500
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // Создаём JSON-ответ с данными об ошибке
        $response = new JsonResponse($data, $statusCode);

        // Устанавливаем новый ответ в событие
        $event->setResponse($response);
    }
}