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
use Symfony\Component\Uid\Uuid;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Cards API"
 * )
 * @OA\Schema(schema="cardId", type="string", format="uuid")
 */
#[Route('/api/v1/boards/{boardId}/cards', requirements: ['boardId' => self::UUID_REGEX])]
final class CardController extends ApiController
{
    #[Route('/{cardId}', 'card', ['cardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boards/{boardId}/cards",
     *     tags={"Cards"},
     *     operationId="createCard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
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
        $data += $request->toArray();
        $data += ['card_id' => Uuid::v4()->toRfc4122()];

        $this->send($data, CreateCardCommand::class);

        return $this->created('card', ['boardId' => $boardId, 'cardId' => $data['card_id']]);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/boards/{boardId}/cards/{cardId}",
     *     tags={"Cards"},
     *     operationId="updateCard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="cardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/cardId")),
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
     *     path="/api/v1/boards/{boardId}/cards/{cardId}",
     *     tags={"Cards"},
     *     operationId="removeCard",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="cardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/cardId")),
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
