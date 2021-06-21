<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiProblemModel;
use App\Exception\ApiProblemException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * This event subscriber will catch every exception and convert it to application/problem+json instead of html response.
 * Class ApiExceptionSubscriber
 * @package App\EventListener
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $statusCode = $e instanceof ExceptionInterface ? $e->getCode() : 500;

        $this->logger->error($e->getMessage());

        if ($e instanceof ApiProblemException) {
            $apiProblem = $e->getApiProblem();
        } else {
            $apiProblem = new ApiProblemModel(
                $statusCode,
                $e->getMessage()
            );

            /*
             * If it's an HttpException message (e.g. for 404, 403),
             * we'll say as a rule that the exception message is safe
             * for the client. Otherwise, it could be some sensitive
             * low-level exception, which should *not* be exposed
             */
            if ($e instanceof HttpExceptionInterface) {
                $apiProblem->setExtraData('detail', $e->getMessage());
            }
        }

        $data = $apiProblem->toArray();

        $response = new JsonResponse(
            $data,
            $apiProblem->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}
