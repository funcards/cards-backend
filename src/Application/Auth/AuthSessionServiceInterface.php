<?php

declare(strict_types=1);

namespace FC\Application\Auth;

interface AuthSessionServiceInterface
{
    /**
     * @throws \Throwable
     */
    public function newSession(string $identifier): Tokens;

    /**
     * @throws \Throwable
     */
    public function refreshSession(string $refreshToken): Tokens;
}
