<?php

declare(strict_types=1);

namespace FC\Application\Auth;

use FC\Application\Auth\JWT\JWTManagerInterface;
use FC\Application\Auth\RefreshToken\RefreshTokenService;

final class AuthSessionService implements AuthSessionServiceInterface
{
    public function __construct(private JWTManagerInterface $manager, private RefreshTokenService $refreshTokenService)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function newSession(string $identifier): Tokens
    {
        $accessToken = $this->manager->create($identifier);
        $refreshToken = $this->refreshTokenService->generate();

        $this->refreshTokenService->set($refreshToken, $identifier);

        return new Tokens($accessToken, $refreshToken);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshSession(string $refreshToken): Tokens
    {
        $identifier = $this->refreshTokenService->get($refreshToken);

        try {
            return $this->newSession($identifier);
        } finally {
            $this->refreshTokenService->delete($refreshToken);
        }
    }
}
