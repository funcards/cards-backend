<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Board\CreateBoardCommand;
use FC\Application\Command\Board\RemoveBoardCommand;
use FC\Application\Command\Board\UpdateBoardCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Boards",
 *     description="Boards API"
 * )
 * @OA\Schema(schema="boardId", type="string", format="uuid")
 */
#[Route('/api/v1/boards')]
final class BoardController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{boardId}",
     *     tags={"Boards"},
     *     operationId="getBoard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(
     *          response=200,
     *          description="Board",
     *          @OA\JsonContent(ref="#/components/schemas/BoardResponse")
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @return Response
     */
    #[Route('/{boardId}', 'board', ['boardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards",
     *     tags={"Boards"},
     *     operationId="listBoard",
     *     @OA\Response(
     *          response=200,
     *          description="Boards",
     *          @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *              @OA\Schema(
     *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BoardResponse"))
     *              )
     *          })
     *     ),
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

        return new Response();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boards",
     *     tags={"Boards"},
     *     operationId="createBoard",
     *     @OA\RequestBody(
     *          request="CreateBoard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateBoard")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Board added successfully",
     *          @OA\Header(header="Location", description="Board URL", @OA\Schema(type="string", format="uri"))
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @return Response
     */
    #[Route(methods: 'POST')]
    public function create(Request $request): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['owner_id' => $this->getUserId()] + $request->toArray() + ['board_id' => $this->uuid()];

        $this->send($data, CreateBoardCommand::class);

        return $this->created('board', ['boardId' => $data['board_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{boardId}",
     *     tags={"Boards"},
     *     operationId="updateBoard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="UpdateBoard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateBoard")
     *     ),
     *     @OA\Response(response=204, description="Board updated successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @return Response
     */
    #[Route('/{boardId}', requirements: ['boardId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()] + $request->toArray();

        $this->send($data, UpdateBoardCommand::class);

        return $this->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{boardId}",
     *     tags={"Boards"},
     *     operationId="removeBoard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(response=204, description="Board removed successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @return Response
     */
    #[Route('/{boardId}', requirements: ['boardId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $this->send(['board_id' => $boardId, 'user_id' => $this->getUserId()], RemoveBoardCommand::class);

        return $this->noContent();
    }
}
