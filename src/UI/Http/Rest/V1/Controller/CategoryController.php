<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Category\CreateCategoryCommand;
use FC\Application\Command\Category\RemoveCategoryCommand;
use FC\Application\Command\Category\UpdateCategoryCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Uid\Uuid;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Categories API"
 * )
 * @OA\Schema(schema="categoryId", type="string", format="uuid")
 */
#[Route('/api/v1/boards/{boardId}/categories', requirements: ['boardId' => self::UUID_REGEX])]
final class CategoryController extends ApiController
{
    #[Route('/{categoryId}', 'category', ['categoryId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $categoryId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boards/{boardId}/categories",
     *     tags={"Categories"},
     *     operationId="createCategory",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
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
        $data += $request->toArray();
        $data += ['category_id' => Uuid::v4()->toRfc4122()];

        $this->send($data, CreateCategoryCommand::class);

        return $this->created('category', ['boardId' => $boardId, 'categoryId' => $data['category_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{boardId}/categories/{categoryId}",
     *     tags={"Categories"},
     *     operationId="updateCategory",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="categoryId", in="path", required=true, @OA\Schema(ref="#/components/schemas/categoryId")),
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
     *     path="/api/v1/boards/{boardId}/categories/{categoryId}",
     *     tags={"Categories"},
     *     operationId="removeCategory",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="categoryId", in="path", required=true, @OA\Schema(ref="#/components/schemas/categoryId")),
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
