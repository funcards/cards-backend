<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Command\Auth\RefreshTokenCommand;
use FC\Application\Command\Auth\SignInCommand;
use FC\Application\Command\Auth\SignUpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication API"
 * )
 */
#[Route('/api/v1')]
final class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/sign-in",
     *     tags={"Authentication"},
     *     operationId="signIn",
     *     @OA\RequestBody(
     *          request="SignIn",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SignIn")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent(ref="#/components/schemas/validationError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     * )
     *
     * @throws \Throwable
     */
    #[Route('/sign-in', 'sign-in', methods: 'POST')]
    public function signIn(Request $request): Response
    {
        $this->debugMethod(__NAMESPACE__);

        $response = $this->send($request->toArray(), SignInCommand::class);

        return $this->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sign-up",
     *     tags={"Authentication"},
     *     operationId="signUp",
     *     @OA\RequestBody(
     *          request="SignUp",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SignUp")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=409, description="Conflict", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent(ref="#/components/schemas/validationError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     * )
     *
     * @throws \Throwable
     */
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
     * @OA\Post(
     *     path="/api/v1/refresh-token",
     *     tags={"Authentication"},
     *     operationId="refreshToken",
     *     @OA\RequestBody(
     *          request="RefreshToken",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RefreshToken")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Returns access token and refresh token",
     *          @OA\JsonContent(ref="#/components/schemas/Tokens")
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/error")),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent(ref="#/components/schemas/validationError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/error")),
     * )
     *
     * @throws \Throwable
     */
    #[Route('/refresh-token', 'refresh-token', methods: 'POST')]
    public function refreshToken(Request $request): Response
    {
        $this->debugMethod(__NAMESPACE__);

        $response = $this->send($request->toArray(), RefreshTokenCommand::class);

        return $this->json($response);
    }
}
