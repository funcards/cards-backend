<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Query\User\UserListQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Users API"
 * )
 */
#[Route('/api/v1/users')]
final class UserController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/users/me",
     *     tags={"Users"},
     *     operationId="me",
     *     @OA\Response(
     *          response=200,
     *          description="User",
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @return Response
     */
    #[Route('/me', methods: 'GET')]
    public function me(): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new UserListQuery(0, 1, [$this->getUserId()]));

        if (0 === \count($response->getData())) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->getData()[0]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{user-id}",
     *     tags={"Users"},
     *     operationId="getUser",
     *     @OA\Parameter(name="user-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/userId")),
     *     @OA\Response(
     *          response=200,
     *          description="User",
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $userId
     * @return Response
     */
    #[Route('/{userId}', 'user', ['userId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $userId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new UserListQuery(0, 1, [$userId]));

        if (0 === \count($response->getData())) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->getData()[0]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     operationId="userList",
     *     @OA\Parameter(name="page-index", in="query", @OA\Schema(ref="#/components/schemas/pageIndex")),
     *     @OA\Parameter(name="page-size", in="query", @OA\Schema(ref="#/components/schemas/pageSize")),
     *     @OA\Parameter(name="users[]", in="query", @OA\Schema(ref="#/components/schemas/users")),
     *     @OA\Parameter(name="emails[]", in="query", @OA\Schema(ref="#/components/schemas/emails")),
     *     @OA\Response(
     *          response=200,
     *          description="Users",
     *          @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *              @OA\Schema(
     *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResponse"))
     *              )
     *          })
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @return Response
     */
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
