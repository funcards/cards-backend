<?php

declare(strict_types=1);

namespace FC\Application\Auth;

interface AuthSessionServiceInterface
{
    /**
     * @param string $identifier
     * @return Tokens
     * @throws \Throwable
     */
    public function newSession(string $identifier): Tokens;

    /**
     * @param string $refreshToken
     * @return Tokens
     * @throws \Throwable
     */
    public function refreshSession(string $refreshToken): Tokens;
}
