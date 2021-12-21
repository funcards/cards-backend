<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\EventSubscriber;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger, private bool $debug)
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

//        if (!\in_array('application/json', $event->getRequest()->getAcceptableContentTypes())) {
//            return;
//        }

        if (!\str_starts_with($event->getRequest()->getPathInfo(), '/api/v1')) {
            return;
        }

        $throwable = $event->getThrowable();

        $error = ['status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        $headers = [];

        if (\array_key_exists($throwable->getCode(), Response::$statusTexts)) {
            $error = ['status' => $throwable->getCode(), 'message' => $throwable->getMessage()];
        }

        if ($throwable instanceof HttpExceptionInterface) {
            $error['status'] = $throwable->getStatusCode();
            $headers = $throwable->getHeaders();
        }

        if (\method_exists($throwable, 'getMessageKey')) {
            $error['message'] = $throwable->getMessageKey();
        }

        if ($throwable instanceof UserNotFoundException) {
            $error = ['status' => Response::HTTP_NOT_FOUND, 'message' => 'User not found.'];
        }

        if ($throwable instanceof ValidationFailedException) {
            $error = ['status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => 'Validation failed.', 'errors' => []];

            /** @var ConstraintViolationInterface $violation */
            foreach ($throwable->getViolations() as $violation) {
                $key = \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $violation->getPropertyPath()));
                $error['errors'][$key][] = $violation->getMessage();
            }
        }

        if ($throwable instanceof UniqueConstraintViolationException) {
            $error = ['status' => Response::HTTP_CONFLICT, 'message' => 'Unique constraint violation.'];
        }

        if (!\array_key_exists('message', $error)) {
            $error['message'] = Response::$statusTexts[$error['status']];
        }

        if ($this->debug) {
            $headers['X-Debug-Exception'] = \rawurlencode($throwable->getMessage());
            $headers['X-Debug-Exception-File'] = \rawurlencode($throwable->getFile()).':'.$throwable->getLine();
        }

        $stack = [];
        do {
            $stack[] = $this->formatExceptionFragment($throwable);
        } while ($throwable = $throwable->getPrevious());

        if ($this->debug) {
            $error['exception'] = $stack;
        }

        $headers += ['Vary' => 'Accept'];
        $error += ['type' => 'https://tools.ietf.org/html/rfc2616#section-10', 'title' => 'An error occurred'];

        $this->logger->error('Exception {status}: {message}', $error + ['exception' => $stack]);

        $event->setResponse(new JsonResponse($error, $error['status'], $headers));
    }

    /**
     * @return array<string, string|int>
     */
    private function formatExceptionFragment(\Throwable $exception): array
    {
        return [
            'type' => $exception::class,
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}
