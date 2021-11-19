<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Command\Command;
use FC\Application\Bus\Command\CommandBus;
use FC\Application\Bus\Query\Query;
use FC\Application\Bus\Query\QueryBus;
use FC\Application\Bus\Query\Response as QueryResponse;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class ApiController implements ServiceSubscriberInterface
{
    protected const UUID_REGEX = '^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$';

    /**
     * @param ContainerInterface $container
     */
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedServices(): array
    {
        return [
            'logger' => LoggerInterface::class,
            'url_generator' => UrlGeneratorInterface::class,
            'token_storage' => TokenStorageInterface::class,
            'denormalizer' => DenormalizerInterface::class,
            'serializer' => SerializerInterface::class,
            'query_bus' => QueryBus::class,
            'command_bus' => CommandBus::class,
        ];
    }

    /**
     * @param Query $query
     * @return QueryResponse
     */
    protected function askQuery(Query $query): QueryResponse
    {
        return $this->container->get('query_bus')->ask($query);
    }

    /**
     * @param Command $command
     * @return mixed
     */
    protected function sendCommand(Command $command): mixed
    {
        return $this->container->get('command_bus')->send($command);
    }

    /**
     * @param array<string, mixed> $data
     * @param string $command
     * @psalm-param class-string $command
     *
     * @return mixed
     */
    protected function send(array $data, string $command): mixed
    {
        return $this->sendCommand($this->command($data, $command));
    }

    /**
     * @template T of Command
     *
     * @param array<string, mixed> $data
     * @param string $className
     * @phpstan-param class-string<T> $className
     *
     * @return Command
     * @phpstan-return T
     */
    protected function command(array $data, string $className): Command
    {
        $context = [];

        if (\defined($c = $className . '::DEFAULT')) {
            $context[AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS] = [
                $className => \constant($c),
            ];
        }

        return $this->container->get('denormalizer')->denormalize($data, $className, null, $context);
    }

    /**
     * Returns absolute route url
     */
    protected function url(string $route, array $parameters = []): string
    {
        return $this->container->get('url_generator')->generate(
            $route,
            $parameters,
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }

    /**
     * @return string
     */
    protected function getUserId(): string
    {
        return $this->container->get('token_storage')->getToken()->getUserIdentifier();
    }

    /**
     * @return LoggerInterface
     */
    protected function logger(): LoggerInterface
    {
        return $this->container->get('logger');
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     */
    protected function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->container->get('serializer')->serialize(
            $data,
            'json',
            \array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context)
        );

        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @param string $route
     * @param array<string, mixed> $parameters
     * @return Response
     */
    protected function created(string $route, array $parameters = []): Response
    {
        return new Response(status: Response::HTTP_CREATED, headers: ['Location' => $this->url($route, $parameters)]);
    }

    /**
     * @return Response
     */
    protected function noContent(): Response
    {
        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $method
     */
    protected function debugMethod(string $method): void
    {
        $this->logger()->debug('ACTION -- {method}', ['method' => $method]);
    }
}
