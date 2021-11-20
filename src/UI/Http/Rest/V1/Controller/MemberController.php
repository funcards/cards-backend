<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Board\AddMemberCommand;
use FC\Application\Command\Board\RemoveMemberCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Members",
 *     description="Members API"
 * )
 */
#[Route('/api/v1/boards/{boardId}/members', requirements: ['boardId' => self::UUID_REGEX])]
final class MemberController extends ApiController
{
    /**
     * @OA\Put(
     *     path="/api/v1/boards/{board-id}/members",
     *     tags={"Members"},
     *     operationId="addMember",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="AddMember",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AddMember")
     *     ),
     *     @OA\Response(response=204, description="Board member added successfully"),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=409, description="Conflict", @OA\JsonContent(ref="#/components/schemas/error")),
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
    #[Route(methods: 'PUT')]
    public function add(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()] + $request->toArray();

        $this->send($data, AddMemberCommand::class);

        return $this->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boards/{board-id}/members/{member-id}",
     *     tags={"Members"},
     *     operationId="removeMember",
     *     @OA\Parameter(name="board-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="member-id", in="path", required=true, @OA\Schema(ref="#/components/schemas/userId")),
     *     @OA\Response(response=204, description="Board member removed successfully"),
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
     * @param string $memberId
     * @return Response
     */
    #[Route('/{memberId}', requirements: ['memberId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $memberId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'member_id' => $memberId];

        $this->send($data, RemoveMemberCommand::class);

        return $this->noContent();
    }
}
