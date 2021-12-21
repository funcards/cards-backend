<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Command\Board\CreateBoardCommand;
use FC\Application\Command\Board\RemoveBoardCommand;
use FC\Application\Command\Board\UpdateBoardCommand;
use FC\Application\Query\Board\BoardListQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Boards",
 *     description="Boards API"
 * )
 */
#[Route('/api/v1/boards')]
final class BoardController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}",
     *     tags={"Boards"},
     *     operationId="getBoard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(
     *          response=200,
     *          description="Board",
     *          @OA\JsonContent(ref="#/components/schemas/BoardResponse")
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
     */
    #[Route('/{boardId}', 'board', ['boardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        /** @var PaginatedResponse $response */
        $response = $this->ask(new BoardListQuery($this->getUserId(), 0, 1, $boardId));

        if ([] === $response->getData()) {
            throw $this->createNotFoundException();
        }

        return $this->json($response->getData()[0]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards",
     *     tags={"Boards"},
     *     operationId="boardList",
     *     @OA\Parameter(name="page-index", in="query", @OA\Schema(ref="#/components/schemas/pageIndex")),
     *     @OA\Parameter(name="page-size", in="query", @OA\Schema(ref="#/components/schemas/pageSize")),
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
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     */
    #[Route(methods: 'GET')]
    public function list(Request $request): Response
    {
        $this->debugMethod(__METHOD__);

        $response = $this->ask(
            new BoardListQuery(
                $this->getUserId(),
                (int)$request->query->get('page-index'),
                (int)$request->query->get('page-size'),
            )
        );

        return $this->json($response);
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
     *     path="/api/v1/boards/{board-id}",
     *     tags={"Boards"},
     *     operationId="updateBoard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="UpdateBoard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateBoard")
     *     ),
     *     @OA\Response(response=204, description="Board updated successfully"),
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
     *     path="/api/v1/boards/{board-id}",
     *     tags={"Boards"},
     *     operationId="removeBoard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(response=204, description="Board removed successfully"),
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
     */
    #[Route('/{boardId}', requirements: ['boardId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $this->send(['board_id' => $boardId, 'user_id' => $this->getUserId()], RemoveBoardCommand::class);

        return $this->noContent();
    }
}
