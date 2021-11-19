<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Tag\CreateTagCommand;
use FC\Application\Command\Tag\RemoveTagCommand;
use FC\Application\Command\Tag\UpdateTagCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tags API"
 * )
 * @OA\Schema(schema="tagId", type="string", format="uuid")
 */
#[Route('/api/v1/boards/{boardId}/tags', requirements: ['boardId' => self::UUID_REGEX])]
final class TagController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{boardId}/tags/{tagId}",
     *     tags={"Tags"},
     *     operationId="getTag",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tagId", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\Response(
     *          response=200,
     *          description="Tag",
     *          @OA\JsonContent(ref="#/components/schemas/TagResponse")
     *     ),
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

        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards/{boardId}/tags",
     *     tags={"Tags"},
     *     operationId="listTag",
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

        return new Response();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boards/{boardId}/tags",
     *     tags={"Tags"},
     *     operationId="createTag",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
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
     *     path="/api/v1/boards/{boardId}/tags/{tagId}",
     *     tags={"Tags"},
     *     operationId="updateTag",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tagId", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\RequestBody(
     *          request="UpdateTag",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTag")
     *     ),
     *     @OA\Response(response=204, description="Board tag updated successfully"),
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
     *     path="/api/v1/boards/{boardId}/tags/{tagId}",
     *     tags={"Tags"},
     *     operationId="removeTag",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="tagId", in="path", required=true, @OA\Schema(ref="#/components/schemas/tagId")),
     *     @OA\Response(response=204, description="Board tag removed successfully"),
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
