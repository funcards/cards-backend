<?php

declare(strict_types=1);

namespace FC\Application\Auth;

interface AuthSessionServiceInterface
{
    /**
     * @param string $identifier
     * @return AuthSession
     * @throws \Throwable
     */
    public function newSession(string $identifier): AuthSession;

    /**
     * @param string $refreshToken
     * @return AuthSession
     * @throws \Throwable
     */
    public function refreshSession(string $refreshToken): AuthSession;
}
