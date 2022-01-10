<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Auth\RefreshTokenCommand;
use FC\Application\Command\Auth\SignInCommand;
use FC\Application\Command\Auth\SignUpCommand;
use FC\UI\Http\Rest\OpenApi\OAResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
final class AuthController extends ApiController
{
    /**
     * @throws \Throwable
     */
    #[Post(
        path: '/api/v1/sign-in',
        operationId: 'signIn',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/SignIn'),
        ),
        tags: ['Authentication'],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Returns access token and refresh token',
                content: new JsonContent(ref: '#/components/schemas/Tokens'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/sign-in', 'sign-in', methods: 'POST')]
    public function signIn(Request $request): Response
    {
        $this->debugMethod(__NAMESPACE__);

        $response = $this->send($request->toArray(), SignInCommand::class);

        return $this->json($response);
    }

    /**
     * @throws \Throwable
     */
    #[Post(
        path: '/api/v1/sign-up',
        operationId: 'signUp',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/SignUp'),
        ),
        tags: ['Authentication'],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Returns access token and refresh token',
                content: new JsonContent(ref: '#/components/schemas/Tokens'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/sign-up', 'sign-up', methods: 'POST')]
    public function signUp(Request $request): Response
    {
        $this->debugMethod(__NAMESPACE__);

        $data = $request->toArray();
        unset($data['roles']);

        $response = $this->send($data, SignUpCommand::class);

        return $this->json($response);
    }

    /**
     * @throws \Throwable
     */
    #[Post(
        path: '/api/v1/refresh-token',
        operationId: 'refreshToken',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(ref: '#/components/schemas/RefreshToken'),
        ),
        tags: ['Authentication'],
        responses: [
            new OAResponse(
                response: 200,
                description: 'Returns access token and refresh token',
                content: new JsonContent(ref: '#/components/schemas/Tokens'),
            ),
            new OAResponse(ref: '#/components/responses/BadRequest', response: 400),
            new OAResponse(ref: '#/components/responses/UnprocessableEntity', response: 422),
            new OAResponse(ref: '#/components/responses/InternalServer', response: 500),
        ],
    )]
    #[Route('/refresh-token', 'refresh-token', methods: 'POST')]
    public function refreshToken(Request $request): Response
    {
        $this->debugMethod(__NAMESPACE__);

        $response = $this->send($request->toArray(), RefreshTokenCommand::class);

        return $this->json($response);
    }
}
