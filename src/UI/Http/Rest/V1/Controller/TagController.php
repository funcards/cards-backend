<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Tag\CreateTagCommand;
use FC\Application\Command\Tag\RemoveTagCommand;
use FC\Application\Command\Tag\UpdateTagCommand;
use FC\Application\Query\Tag\TagListQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tags API"
 * )
 */
#[Route('/api/v1/boards/{boardId}/tags', requirements: ['boardId' => self::UUID_REGEX])]
final class TagController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/tags/{tag-id}",
     *     tags={"Tags"},
     *     operationId="getTag",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tag-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\Response(
     *          response=200,
     *          description="Tag",
     *          @OA\JsonContent(ref="#/components/schemas/TagResponse")
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
     * @param string $boardId
     * @param string $tagId
     * @return Response
     */
    #[Route('/{tagId}', 'tag', ['tagId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new TagListQuery($boardId, $this->getUserId(), 0, 1, $tagId));

        if (0 === \count($response->getData())) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->getData()[0]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/tags",
     *     tags={"Tags"},
     *     operationId="tagList",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="page-index", in="query", @OA\Schema(ref="#/components/schemas/pageIndex")),
     *     @OA\Parameter(name="page-size", in="query", @OA\Schema(ref="#/components/schemas/pageSize")),
     *     @OA\Response(
     *          response=200,
     *          description="Tags",
     *          @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *              @OA\Schema(
     *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TagResponse"))
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
     * @param string $boardId
     * @return Response
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/boards/{board-id}/tags",
     *     tags={"Tags"},
     *     operationId="createTag",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="CreateTag",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateTag")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Board tag added successfully",
     *          @OA\Header(header="Location", description="Tag URL", @OA\Schema(type="string", format="uri"))
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent(ref="#/components/schemas/validationError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
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
        $data += $request->toArray() + ['tag_id' => $this->uuid()];

        $this->send($data, CreateTagCommand::class);

        return $this->created('tag', ['boardId' => $boardId, 'tagId' => $data['tag_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{board-id}/tags/{tag-id}",
     *     tags={"Tags"},
     *     operationId="updateTag",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tag-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\RequestBody(
     *          request="UpdateTag",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTag")
     *     ),
     *     @OA\Response(response=204, description="Board tag updated successfully"),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent(ref="#/components/schemas/validationError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @param string $tagId
     * @return Response
     */
    #[Route('/{tagId}', requirements: ['tagId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'tag_id' => $tagId];
        $data += $request->toArray();

        $this->send($data, UpdateTagCommand::class);

        return $this->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{board-id}/tags/{tag-id}",
     *     tags={"Tags"},
     *     operationId="removeTag",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tag-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\Response(response=204, description="Board tag removed successfully"),
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
     * @param string $boardId
     * @param string $tagId
     * @return Response
     */
    #[Route('/{tagId}', requirements: ['tagId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'tag_id' => $tagId];

        $this->send($data, RemoveTagCommand::class);

        return $this->noContent();
    }
}
