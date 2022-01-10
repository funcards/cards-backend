<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Card\BatchUpdateCardCommand;
use FC\Application\Command\Card\CreateCardCommand;
use FC\Application\Command\Card\RemoveCardCommand;
use FC\Application\Command\Card\UpdateCardCommand;
use FC\Application\Query\Card\CardListQuery;
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

#[Route('/api/v1/boards/{boardId}/cards', requirements: ['boardId' => self::UUID_REGEX])]
final class CardController extends ApiController
{
    #[Get(
        path: '/api/v1/boards/{board-id}/cards/{card-id}',
        operationId: 'getCard',
        security: [['Bearer' => []]],
        tags: ['Cards'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/cardId'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Card',
                content: new JsonContent(ref: '#/components/schemas/CardResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{cardId}', 'card', ['cardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new CardListQuery($boardId, $this->getUserId(), 0, 1, [], $cardId));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/boards/{board-id}/cards',
        operationId: 'cardList',
        security: [['Bearer' => []]],
        tags: ['Cards'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/pageIndex'),
            new Parameter(ref: '#/components/parameters/pageSize'),
            new Parameter(ref: '#/components/parameters/categories'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Cards',
                content: new JsonContent(allOf: [
                    new Schema(ref: '#/components/schemas/PaginatedResponse'),
                    new Schema(required: ['data'], properties: [
                        new Property(
                            property: 'data',
                            type: 'array',
                            items: new Items(ref: '#/components/schemas/CardResponse'),
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
    public function list(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $response = $this->ask(
            new CardListQuery(
                $boardId,
                $this->getUserId(),
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
                $request->query->all('categories'),
            )
        );

        return $this->json($response);
    }

    #[Post(
        path: '/api/v1/boards/{board-id}/cards',
        operationId: 'createCard',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/CreateCard'),
        ),
        tags: ['Cards'],
        parameters: [new Parameter(ref: '#/components/parameters/boardId')],
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
    public function create(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()];
        $data += $request->toArray() + ['card_id' => $this->uuid()];

        $this->send($data, CreateCardCommand::class);

        return $this->created('card', ['boardId' => $boardId, 'cardId' => $data['card_id']]);
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}/cards',
        operationId: 'batchUpdateCards',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(items: new Items(allOf: [
                new Schema(properties: [new Property(property: 'card_id', type: 'string', format: 'uuid')]),
                new Schema(ref: '#/components/schemas/UpdateCard')
            ])),
        ),
        tags: ['Cards'],
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
    #[Route(methods: 'PATCH')]
    public function batchUpdate(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $userId = $this->getUserId();
        $commands = [];

        foreach ($request->toArray() as $data) {
            $commands[] = $this->command(
                ['board_id' => $boardId, 'user_id' => $userId] + (array)$data + ['card_id' => ''],
                UpdateCardCommand::class
            );
        }

        $this->sendCommand(new BatchUpdateCardCommand(...$commands));

        return $this->noContent();
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}/cards/{card-id}',
        operationId: 'updateCard',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/UpdateCard'),
        ),
        tags: ['Cards'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/cardId'),
        ],
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
    #[Route('/{cardId}', requirements: ['cardId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'card_id' => $cardId];
        $data += $request->toArray();

        $this->send($data, UpdateCardCommand::class);

        return $this->noContent();
    }

    #[Delete(
        path: '/api/v1/boards/{board-id}/cards/{card-id}',
        operationId: 'removeCard',
        security: [['Bearer' => []]],
        tags: ['Cards'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/cardId'),
        ],
        responses: [
            new OAResponse(ref: '#/components/responses/NoContent', response: 204),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{cardId}', requirements: ['cardId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'card_id' => $cardId];

        $this->send($data, RemoveCardCommand::class);

        return $this->noContent();
    }
}
