<?php

declare(strict_types=1);

namespace FC\Application\Auth\RefreshToken;

use FC\Application\Auth\RefreshToken\Exception\InvalidRefreshTokenException;

interface RefreshTokenService
{
    /**
     * Generate new unique refresh token
     */
    public function generate(): string;

    /**
     * @throws InvalidRefreshTokenException
     */
    public function get(string $refreshToken): string;

    public function set(string $refreshToken, string $payload): void;

    public function delete(string $refreshToken): void;
}
