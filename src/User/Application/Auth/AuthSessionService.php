<?php

declare(strict_types=1);

namespace FC\User\Application\Auth;

use FC\User\Application\JWT\JWTManagerInterface;
use FC\User\Application\RefreshToken\RefreshTokenService;

final class AuthSessionService implements AuthSessionServiceInterface
{
    /**
     * @param JWTManagerInterface $manager
     * @param RefreshTokenService $refreshTokenService
     */
    public function __construct(private JWTManagerInterface $manager, private RefreshTokenService $refreshTokenService)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function newSession(string $identifier): AuthSession
    {
        $accessToken = $this->manager->create($identifier);
        $refreshToken = $this->refreshTokenService->generate();

        $this->refreshTokenService->set($refreshToken, $identifier);

        return new AuthSession($accessToken, $refreshToken);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshSession(string $refreshToken): AuthSession
    {
        $identifier = $this->refreshTokenService->get($refreshToken);

        try {
            return $this->newSession($identifier);
        } finally {
            $this->refreshTokenService->delete($refreshToken);
        }
    }
}
