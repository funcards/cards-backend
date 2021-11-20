<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Card\CreateCardCommand;
use FC\Application\Command\Card\RemoveCardCommand;
use FC\Application\Command\Card\UpdateCardCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Cards API"
 * )
 */
#[Route('/api/v1/boards/{boardId}/cards', requirements: ['boardId' => self::UUID_REGEX])]
final class CardController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/cards/{card-id}",
     *     tags={"Cards"},
     *     operationId="getCard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="card-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/cardId")),
     *     @OA\Response(
     *          response=200,
     *          description="Card",
     *          @OA\JsonContent(ref="#/components/schemas/CardResponse")
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @param string $cardId
     * @return Response
     */
    #[Route('/{cardId}', 'card', ['cardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boards/{board-id}/cards",
     *     tags={"Cards"},
     *     operationId="listCard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Response(
     *          response=200,
     *          description="Cards",
     *          @OA\JsonContent(allOf={
     *              @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *              @OA\Schema(
     *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CardResponse"))
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
     *     path="/api/v1/boards/{board-id}/cards",
     *     tags={"Cards"},
     *     operationId="createCard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="CreateCard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateCard")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Board card added successfully",
     *          @OA\Header(header="Location", description="Card URL", @OA\Schema(type="string", format="uri"))
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
        $data += $request->toArray() + ['card_id' => $this->uuid()];

        $this->send($data, CreateCardCommand::class);

        return $this->created('card', ['boardId' => $boardId, 'cardId' => $data['card_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{board-id}/cards/{card-id}",
     *     tags={"Cards"},
     *     operationId="updateCard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="card-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/cardId")),
     *     @OA\RequestBody(
     *          request="UpdateCard",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCard")
     *     ),
     *     @OA\Response(response=204, description="Board card updated successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param string $boardId
     * @param string $cardId
     * @return Response
     */
    #[Route('/{cardId}', requirements: ['cardId' => self::UUID_REGEX], methods: 'PATCH')]
    public function update(Request $request, string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'card_id' => $cardId];
        $data += $request->toArray();

        $this->send($data, UpdateCardCommand::class);

        return $this->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{board-id}/cards/{card-id}",
     *     tags={"Cards"},
     *     operationId="removeCard",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="card-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/cardId")),
     *     @OA\Response(response=204, description="Board card removed successfully"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * @param string $boardId
     * @param string $cardId
     * @return Response
     */
    #[Route('/{cardId}', requirements: ['cardId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'card_id' => $cardId];

        $this->send($data, RemoveCardCommand::class);

        return $this->noContent();
    }
}
