<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Tag\CreateTagCommand;
use FC\Application\Command\Tag\RemoveTagCommand;
use FC\Application\Command\Tag\UpdateTagCommand;
use FC\Application\Query\Tag\TagListQuery;
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

#[Route('/api/v1/boards/{boardId}/tags', requirements: ['boardId' => self::UUID_REGEX])]
final class TagController extends ApiController
{
    #[Get(
        path: '/api/v1/boards/{board-id}/tags/{tag-id}',
        operationId: 'getTag',
        security: [['Bearer' => []]],
        tags: ['Tags'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/tagId'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Tag',
                content: new JsonContent(ref: '#/components/schemas/TagResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{tagId}', 'tag', ['tagId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new TagListQuery($boardId, $this->getUserId(), 0, 1, $tagId));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/boards/{board-id}/tags',
        operationId: 'tagList',
        security: [['Bearer' => []]],
        tags: ['Tags'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/pageIndex'),
            new Parameter(ref: '#/components/parameters/pageSize'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Tags',
                content: new JsonContent(allOf: [
                    new Schema(ref: '#/components/schemas/PaginatedResponse'),
                    new Schema(required: ['data'], properties: [
                        new Property(
                            property: 'data',
                            type: 'array',
                            items: new Items(ref: '#/components/schemas/TagResponse'),
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
            new TagListQuery(
                $boardId,
                $this->getUserId(),
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
            )
        );

        return $this->json($response);
    }

    #[Post(
        path: '/api/v1/boards/{board-id}/tags',
        operationId: 'createTag',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/CreateTag'),
        ),
        tags: ['Tags'],
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
        $data += $request->toArray() + ['tag_id' => $this->uuid()];

        $this->send($data, CreateTagCommand::class);

        return $this->created('tag', ['boardId' => $boardId, 'tagId' => $data['tag_id']]);
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}/tags/{tag-id}',
        operationId: 'updateTag',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/UpdateTag'),
        ),
        tags: ['Tags'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/tagId'),
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
    #[Route('/{tagId}', requirements: ['tagId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'tag_id' => $tagId];
        $data += $request->toArray();

        $this->send($data, UpdateTagCommand::class);

        return $this->noContent();
    }

    #[Delete(
        path: '/api/v1/boards/{board-id}/tags/{tag-id}',
        operationId: 'removeTag',
        security: [['Bearer' => []]],
        tags: ['Tags'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/tagId'),
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
    #[Route('/{tagId}', requirements: ['tagId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'tag_id' => $tagId];

        $this->send($data, RemoveTagCommand::class);

        return $this->noContent();
    }
}
