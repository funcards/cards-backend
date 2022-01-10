<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Board\CreateBoardCommand;
use FC\Application\Command\Board\RemoveBoardCommand;
use FC\Application\Command\Board\UpdateBoardCommand;
use FC\Application\Query\Board\BoardListQuery;
use FC\UI\Http\Rest\OpenApi\OAResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/boards')]
final class BoardController extends ApiController
{
    #[Get(
        path: '/api/v1/boards/{board-id}',
        operationId: 'getBoard',
        security: [['Bearer' => []]],
        tags: ['Boards'],
        parameters: [new Parameter(ref: '#/components/parameters/boardId')],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Board',
                content: new JsonContent(ref: '#/components/schemas/BoardResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{boardId}', 'board', ['boardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new BoardListQuery($this->getUserId(), 0, 1, $boardId));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/boards',
        operationId: 'boardList',
        security: [['Bearer' => []]],
        tags: ['Boards'],
        parameters: [
            new Parameter(ref: '#/components/parameters/pageIndex'),
            new Parameter(ref: '#/components/parameters/pageSize'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Boards',
                content: new JsonContent(allOf: [
                    new Schema(ref: '#/components/schemas/PaginatedResponse'),
                    new Schema(properties: [
                        new Property(
                            property: 'data',
                            type: 'array',
                            items: new Items(ref: '#/components/schemas/BoardResponse'),
                        ),
                    ]),
                ]),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route(methods: 'GET')]
    public function list(Request $request): Response
    {
        $this->debugMethod(__METHOD__);

        $response = $this->ask(
            new BoardListQuery(
                $this->getUserId(),
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
            )
        );

        return $this->json($response);
    }

    #[Post(
        path: '/api/v1/boards',
        operationId: 'createBoard',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/CreateBoard'),
        ),
        tags: ['Boards'],
        responses: [
            new OAResponse(ref: '#/components/responses/Created', response: 201),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route(methods: 'POST')]
    public function create(Request $request): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['owner_id' => $this->getUserId()] + $request->toArray() + ['board_id' => $this->uuid()];

        $this->send($data, CreateBoardCommand::class);

        return $this->created('board', ['boardId' => $data['board_id']]);
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}',
        operationId: 'updateBoard',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/UpdateBoard'),
        ),
        tags: ['Boards'],
        parameters: [new Parameter(ref: '#/components/parameters/boardId')],
        responses: [
            new OAResponse(ref: '#/components/responses/NoContent', response: 204),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{boardId}', requirements: ['boardId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()] + $request->toArray();

        $this->send($data, UpdateBoardCommand::class);

        return $this->noContent();
    }

    #[Delete(
        path: '/api/v1/boards/{board-id}',
        operationId: 'removeBoard',
        security: [['Bearer' => []]],
        tags: ['Boards'],
        parameters: [new Parameter(ref: '#/components/parameters/boardId')],
        responses: [
            new OAResponse(ref: '#/components/responses/NoContent', response: 204),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{boardId}', requirements: ['boardId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $this->send(['board_id' => $boardId, 'user_id' => $this->getUserId()], RemoveBoardCommand::class);

        return $this->noContent();
    }
}
