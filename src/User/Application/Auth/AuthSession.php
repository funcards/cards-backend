<?php

declare(strict_types=1);

namespace FC\User\Application\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="Tokens", required={"access_token", "refresh_token"})
 */
final class AuthSession
{
    /**
     * @param string $accessToken
     * @param string $refreshToken
     */
    public function __construct(
        /** @OA\Property(property="access_token") */ private string $accessToken,
        /** @OA\Property(property="refresh_token") */ private string $refreshToken,
    ) {
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
