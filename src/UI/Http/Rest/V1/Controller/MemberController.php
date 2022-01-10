<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Board\AddMemberCommand;
use FC\Application\Command\Board\RemoveMemberCommand;
use FC\UI\Http\Rest\OpenApi\OAResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/boards/{boardId}/members', requirements: ['boardId' => self::UUID_REGEX])]
final class MemberController extends ApiController
{
    #[Put(
        path: '/api/v1/boards/{board-id}/members',
        operationId: 'addMember',
        security: [['Bearer' => []]],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/AddMember'),
        ),
        tags: ['Members'],
        parameters: [new Parameter(ref: '#/components/parameters/boardId')],
        responses: [
            new OAResponse(ref: '#/components/responses/NoContent', response: 204),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/Conflict', response: 409),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route(methods: 'PUT')]
    public function add(Request $request, string $boardId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId()] + $request->toArray();

        $this->send($data, AddMemberCommand::class);

        return $this->noContent();
    }

    #[Delete(
        path: '/api/v1/boards/{board-id}/members/{member-id}',
        operationId: 'removeMember',
        security: [['Bearer' => []]],
        tags: ['Members'],
        parameters: [
            new Parameter(ref: '#/components/parameters/boardId'),
            new Parameter(ref: '#/components/parameters/memberId'),
        ],
        responses: [
            new OAResponse(ref: '#/components/responses/NoContent', response: 204),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/Unauthorized', response: 401),
            new OAResponse(ref: '#/components/responses/Forbidden', response: 403),
            new OAResponse(ref: '#/components/responses/NotFound', response: 404),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/{memberId}', requirements: ['memberId' => self::UUID_REGEX], methods: 'DELETE')]
    public function remove(string $boardId, string $memberId): Response
    {
        $this->debugMethod(__METHOD__);

        $data = ['board_id' => $boardId, 'user_id' => $this->getUserId(), 'member_id' => $memberId];

        $this->send($data, RemoveMemberCommand::class);

        return $this->noContent();
    }
}
