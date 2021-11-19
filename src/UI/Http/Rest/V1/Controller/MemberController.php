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
 * @OA\Schema(schema="memberId", type="string", format="uuid")
 */
#[Route('/api/v1/boards/{boardId}/members', requirements: ['boardId' => self::UUID_REGEX])]
final class MemberController extends ApiController
{
    /**
     * @OA\Put(
     *     path="/api/v1/boards/{boardId}/members",
     *     tags={"Members"},
     *     operationId="addMember",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\RequestBody(
     *          request="AddMember",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AddMember")
     *     ),
     *     @OA\Response(response=204, description="Board member added successfully"),
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
     *     path="/api/v1/boards/{boardId}/members/{memberId}",
     *     tags={"Members"},
     *     operationId="removeMember",
     *     @OA\Parameter(name="boardId", in="path", required=true, @OA\Schema(ref="#/components/schemas/boardId")),
     *     @OA\Parameter(name="memberId", in="path", required=true, @OA\Schema(ref="#/components/schemas/memberId")),
     *     @OA\Response(response=204, description="Board member removed successfully"),
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
