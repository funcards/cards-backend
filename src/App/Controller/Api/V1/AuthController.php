<?php

declare(strict_types=1);

namespace FC\App\Controller\Api\V1;

use FC\Shared\Application\Command\Command;
use FC\Shared\Application\Command\CommandBus;
use FC\User\Application\Command\Create\CreateCommand;
use FC\User\Application\Command\Refresh\RefreshTokenCommand;
use FC\User\Application\Command\SignIn\SignInCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="All api for authentication"
 * )
 */
#[Route('/api/v1')]
final class AuthController
{
    /**
     * @param DenormalizerInterface $denormalizer
     * @param SerializerInterface $serializer
     * @param CommandBus $commandBus
     * @param LoggerInterface $logger
     */
    public function __construct(
        private DenormalizerInterface $denormalizer,
        private SerializerInterface $serializer,
        private CommandBus $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sign-in",
     *     tags={"Authentication"},
     *     operationId="signIn",
     *     @OA\RequestBody(
     *          request="SignIn",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SignIn")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     )
     * )
     *
     * @param Request $request
     * @return Response
     * @throws \Throwable
     */
    #[Route('/sign-in', 'sign-in', methods: 'POST')]
    public function signIn(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $response = $this->commandBus->send($this->command($request, SignInCommand::class));

        return new JsonResponse($this->serializer->serialize($response, 'json'), json: true);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sign-up",
     *     tags={"Authentication"},
     *     operationId="signUp",
     *     @OA\RequestBody(
     *          request="SignUp",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SignUp")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     )
     * )
     *
     * @param Request $request
     * @return Response
     * @throws \Throwable
     */
    #[Route('/sign-up', 'sign-up', methods: 'POST')]
    public function signUp(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $response = $this->commandBus->send($this->command($request, CreateCommand::class));

        return new JsonResponse($this->serializer->serialize($response, 'json'), json: true);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/refresh-token",
     *     tags={"Authentication"},
     *     operationId="refreshToken",
     *     @OA\RequestBody(
     *          request="RefreshToken",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RefreshToken")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     )
     * )
     *
     * @param Request $request
     * @return Response
     * @throws \Throwable
     */
    #[Route('/refresh-token', 'refresh-token', methods: 'POST')]
    public function refreshToken(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $response = $this->commandBus->send($this->command($request, RefreshTokenCommand::class));

        return new JsonResponse($this->serializer->serialize($response, 'json'), json: true);
    }

    /**
     * @template T of Command
     *
     * @param Request $request
     * @param string $className
     * @phpstan-param class-string<T> $className
     *
     * @return Command
     * @phpstan-return T
     *
     * @throws ExceptionInterface
     */
    private function command(Request $request, string $className): Command
    {
        $data = $request->toArray();
        unset($data['roles']);

        return $this->denormalizer->denormalize($data, $className, null, [
            AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                $className => \constant($className . '::DEFAULT'),
            ],
        ]);
    }
}
