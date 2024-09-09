<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            while ($exception instanceof HandlerFailedException) {
                $exception = $exception->getPrevious();
            }
        }

        if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof BadRequestHttpException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof BadRequestHttpException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        }

        if (!isset($response)) {
            $code = $exception->getCode() && in_array($exception->getCode(), array_keys(Response::$statusTexts))
                ? $exception->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            $response  = new JsonResponse(['message' => $exception->getMessage()], $code);
            $this->logger->critical($exception->getMessage());
        }

        $event->setResponse($response);
        $event->allowCustomResponseCode();
    }
}
