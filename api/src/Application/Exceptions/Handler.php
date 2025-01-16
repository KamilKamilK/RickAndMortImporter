<?php

namespace App\Exceptions;

use Domains\Accounts\Exceptions\Auth\AuthenticationException;
use Domains\Common\Exceptions\DomainException;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Common\Exceptions\ModelNotFoundException;
use Domains\Events\Exceptions\EventPublicationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler
{
    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $env = getenv('APP_ENV');

        if (empty($env)) {
            throw new \RuntimeException('APP_ENV not found');
        }

        if ($exception instanceof DomainValidationException) {
            $response = new JsonResponse([
                'type' => 'requestInputValidation',
                'message' => $exception->getMessage(),
                'fields_errors' => $exception->formattedErrors()
            ], Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'type' => 'httpError',
                'message' => $exception->getMessage(),
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());
        } else {
            $data = [
                'type' => 'unknownError',
                'message' => $exception->getMessage(),
                'error' => 'Internal server error'
            ];
            if ($env === 'dev') {
                $data['message'] = ': ' . $exception->getMessage();
                $data['trace'] = ': ' . $exception->getTraceAsString();
            }

            $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}