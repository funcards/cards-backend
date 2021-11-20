<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Category\CreateCategoryCommand;
use FC\Application\Command\Category\RemoveCategoryCommand;
use FC\Application\Command\Category\UpdateCategoryCommand;
use FC\Application\Query\Category\CategoryListQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Categories API"
 * )
 */
#[Route('/api/v1/boards/{boardId}/categories', requirements: ['boardId' => self::UUID_REGEX])]
final class CategoryController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/categories/{category-id}",
     *     tags={"Categories"},
     *     operationId="getCategory",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="category-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/categoryId")),
     *     @OA\Response(
     *          response=200,
     *          description="Category",
     *          @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @param string $categoryId
     * @return Response
     */
    #[Route('/{categoryId}', 'category', ['categoryId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new CategoryListQuery($boardId, $this->getUserId(), 0, 1, $categoryId));

        if (0 === \count($response->getData())) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->getData()[0]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/categories",
     *     tags={"Categories"},
     *     operationId="categoryList",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="page-index", in="query", @OA\Schema(ref="#/components/schemas/pageIndex")),
     *     @OA\Parameter(name="page-size", in="query", @OA\Schema(ref="#/components/schemas/pageSize")),
     *     @OA\Response(
     *          response=200,
     *          description="Categories",
     *          @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *              @OA\Schema(
     *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CategoryResponse"))
     *              )
     *          })
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @return Response
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/boards/{board-id}/categories",
     *     tags={"Categories"},
     *     operationId="createCategory",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="CreateCategory",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateCategory")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Board category added successfully",
     *          @OA\Header(header="Location", description="Category URL", @OA\Schema(type="string", format="uri"))
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @return Response
     */
    #[Route(methods: 'POST')]
    public function create(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()];
        $data += $request->toArray() + ['category_id' => $this->uuid()];

        $this->send($data, CreateCategoryCommand::class);

        return $this->created('category', ['boardId' => $boardId, 'categoryId' => $data['category_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{board-id}/categories/{category-id}",
     *     tags={"Categories"},
     *     operationId="updateCategory",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="category-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/categoryId")),
     *     @OA\RequestBody(
     *          request="UpdateCategory",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCategory")
     *     ),
     *     @OA\Response(response=204, description="Board category updated successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @param string $categoryId
     * @return Response
     */
    #[Route('/{categoryId}', requirements: ['categoryId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'category_id' => $categoryId];
        $data += $request->toArray();

        $this->send($data, UpdateCategoryCommand::class);

        return $this->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{board-id}/categories/{category-id}",
     *     tags={"Categories"},
     *     operationId="removeCategory",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="category-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/categoryId")),
     *     @OA\Response(response=204, description="Board category removed successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @param string $categoryId
     * @return Response
     */
    #[Route('/{categoryId}', requirements: ['categoryId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'category_id' => $categoryId];

        $this->send($data, RemoveCategoryCommand::class);

        return $this->noContent();
    }
}
