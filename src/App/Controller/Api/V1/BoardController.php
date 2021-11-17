<?php

declare(strict_types=1);

namespace FC\App\Controller\Api\V1;

use FC\Board\Application\Command\CreateBoardCommand;
use FC\Board\Application\Command\RemoveBoardCommand;
use FC\Board\Application\Command\UpdateBoardCommand;
use FC\Shared\Application\Command\Command;
use FC\Shared\Application\Command\CommandBus;
use FC\Shared\Application\Query\QueryBus;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @OA\Tag(
 *     name="Boards",
 *     description="Boards API"
 * )
 *
 * @OA\Schema(schema="boardId", type="string", format="uuid")
 */
#[Route('/api/v1/boards')]
final class BoardController
{
    private const UUID_REGEX = '^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$';

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param DenormalizerInterface $denormalizer
     * @param SerializerInterface $serializer
     * @param QueryBus $queryBus
     * @param CommandBus $commandBus
     * @param LoggerInterface $logger
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private DenormalizerInterface $denormalizer,
        private SerializerInterface $serializer,
        private QueryBus $queryBus,
        private CommandBus $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/{id}', 'board', ['id' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $id): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        return new Response();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boards",
     *     tags={"Boards"},
     *     operationId="createBoard",
     *     @OA\RequestBody(
     *          request="CreateBoard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateBoard")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Board added successfully",
     *          @OA\Header(header="Location", description="Board URL", @OA\Schema(type="string", format="uri"))
     *     )
     * )
     *
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @throws \Throwable
     */
    #[Route(methods: 'POST')]
    public function create(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $data = ['owner_id' => $this->getUserId()] + $request->toArray() + ['board_id' => Uuid::v4()->toRfc4122()];

        $this->commandBus->send($this->command($data, CreateBoardCommand::class));

        return new Response(status: Response::HTTP_CREATED, headers: [
            'Location' => $urlGenerator->generate(
                'board',
                ['id' => $data['board_id']],
                UrlGeneratorInterface::ABSOLUTE_URL,
            ),
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{id}",
     *     tags={"Boards"},
     *     operationId="updateBoard",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="UpdateBoard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateBoard")
     *     ),
     *     @OA\Response(response=204, description="Board updated successfully")
     * )
     *
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws \Throwable
     */
    #[Route('/{id}', requirements: ['id' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $id): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $data = ['board_id' => $id, 'user_id' => $this->getUserId()] + $request->toArray();

        $this->commandBus->send($this->command($data, UpdateBoardCommand::class));

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{id}",
     *     tags={"Boards"},
     *     operationId="removeBoard",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(response=204, description="Board removed successfully")
     * )
     *
     * @param string $id
     * @return Response
     * @throws \Throwable
     */
    #[Route('/{id}', requirements: ['id' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $id): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $this->commandBus->send(new RemoveBoardCommand($id, $this->getUserId()));

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    private function getUserId(): string
    {
        return $this->tokenStorage->getToken()->getUser()->getUserIdentifier();
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
     *
     * @throws ExceptionInterface
     */
    private function command(array $data, string $className): Command
    {
        $context = [];

        if (\defined($c = $className . '::DEFAULT')) {
            $context[AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS] = [
                $className => \constant($c),
            ];
        }

        return $this->denormalizer->denormalize($data, $className, null, $context);
    }
}
