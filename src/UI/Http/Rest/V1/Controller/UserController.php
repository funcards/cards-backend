<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Query\User\UserListQuery;
use FC\UI\Http\Rest\OpenApi\OAResponse;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/users')]
final class UserController extends ApiController
{
    #[Get(
        path: '/api/v1/users/me',
        operationId: 'me',
        security: [['Bearer' => []]],
        tags: ['Users'],
        responses: [
            new OAResponse(
                response: 200,
                description: 'User',
                content: new JsonContent(ref: '#/components/schemas/UserResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/me', methods: 'GET')]
    public function me(): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new UserListQuery(0, 1, [$this->getUserId()]));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/users/{user-id}',
        operationId: 'getUser',
        security: [['Bearer' => []]],
        tags: ['Users'],
        parameters: [new Parameter(ref: '#/components/parameters/userId')],
        responses: [
            new OAResponse(
                response: 200,
                description: 'User',
                content: new JsonContent(ref: '#/components/schemas/UserResponse'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{userId}', 'user', ['userId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $userId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new UserListQuery(0, 1, [$userId]));

        if ([] === $response->data) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->data[0]);
    }

    #[Get(
        path: '/api/v1/users',
        operationId: 'userList',
        security: [['Bearer' => []]],
        tags: ['Users'],
        parameters: [
            new Parameter(ref: '#/components/parameters/pageIndex'),
            new Parameter(ref: '#/components/parameters/pageSize'),
            new Parameter(ref: '#/components/parameters/users'),
            new Parameter(ref: '#/components/parameters/emails'),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Users',
                content: new JsonContent(allOf: [
                    new Schema(ref: '#/components/schemas/PaginatedResponse'),
                    new Schema(properties: [
                        new Property(
                            property: 'data',
                            type: 'array',
                            items: new Items(ref: '#/components/schemas/UserResponse'),
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
            new UserListQuery(
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
                $request->query->all('users'),
                ...$request->query->all('emails'),
            )
        );

        return $this->json($response);
    }
}
