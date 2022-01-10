<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Category\BatchUpdateCategoryCommand;
use FC\Application\Command\Category\CreateCategoryCommand;
use FC\Application\Command\Category\RemoveCategoryCommand;
use FC\Application\Command\Category\UpdateCategoryCommand;
use FC\Application\Query\Category\CategoryListQuery;
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

#[Route('/api/v1/boards/{boardId}/categories', requirements: ['boardId' => self::UUID_REGEX])]
final class CategoryController extends ApiController
{
    #[Get(
        path: '/api/v1/boards/{board-id}/categories/{category-id}',
        operationId: 'getCategory',
        security: [['Bearer' => []]],
        tags: ['Categories'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/categoryId'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Category',
                content: new JsonContent(ref: '#/components/schemas/CategoryResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{categoryId}', 'category', ['categoryId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new CategoryListQuery($boardId, $this->getUserId(), 0, 1, $categoryId));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/boards/{board-id}/categories',
        operationId: 'categoryList',
        security: [['Bearer' => []]],
        tags: ['Categories'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/pageIndex'),
            new Parameter(ref: '#/components/parameters/pageSize'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Categories',
                content: new JsonContent(allOf: [
                    new Schema(ref: '#/components/schemas/PaginatedResponse'),
                    new Schema(required: ['data'], properties: [
                        new Property(
                            property: 'data',
                            type: 'array',
                            items: new Items(ref: '#/components/schemas/CategoryResponse'),
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
            new CategoryListQuery(
                $boardId,
                $this->getUserId(),
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
            )
        );

        return $this->json($response);
    }

    #[Post(
        path: '/api/v1/boards/{board-id}/categories',
        operationId: 'createCategory',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/CreateCategory'),
        ),
        tags: ['Categories'],
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
        $data += $request->toArray() + ['category_id' => $this->uuid()];

        $this->send($data, CreateCategoryCommand::class);

        return $this->created('category', ['boardId' => $boardId, 'categoryId' => $data['category_id']]);
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}/categories',
        operationId: 'batchUpdateCategories',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(items: new Items(allOf: [
                new Schema(properties: [new Property(property: 'category_id', type: 'string', format: 'uuid')]),
                new Schema(ref: '#/components/schemas/UpdateCategory')
            ])),
        ),
        tags: ['Categories'],
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
                ['board_id' => $boardId, 'user_id' => $userId] + (array)$data + ['category_id' => ''],
                UpdateCategoryCommand::class
            );
        }

        $this->sendCommand(new BatchUpdateCategoryCommand(...$commands));

        return $this->noContent();
    }

    #[Patch(
        path: '/api/v1/boards/{board-id}/categories/{category-id}',
        operationId: 'updateCategory',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/UpdateCategory'),
        ),
        tags: ['Categories'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/categoryId'),
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
    #[Route('/{categoryId}', requirements: ['categoryId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'category_id' => $categoryId];
        $data += $request->toArray();

        $this->send($data, UpdateCategoryCommand::class);

        return $this->noContent();
    }

    #[Delete(
        path: '/api/v1/boards/{board-id}/categories/{category-id}',
        operationId: 'removeCategory',
        security: [['Bearer' => []]],
        tags: ['Categories'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/categoryId'),
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
    #[Route('/{categoryId}', requirements: ['categoryId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'category_id' => $categoryId];

        $this->send($data, RemoveCategoryCommand::class);

        return $this->noContent();
    }
}
