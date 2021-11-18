<?php

declare(strict_types=1);

namespace FC\Application\Auth\RefreshToken;

use FC\Application\Auth\RefreshToken\Exception\InvalidRefreshTokenException;

interface RefreshTokenService
{
    /**
     * Generate new unique refresh token
     *
     * @return string
     */
    public function generate(): string;

    /**
     * @param string $refreshToken
     * @return string
     * @throws InvalidRefreshTokenException
     */
    public function get(string $refreshToken): string;

    /**
     * @param string $refreshToken
     * @param string $payload
     */
    public function set(string $refreshToken, string $payload): void;

    /**
     * @param string $refreshToken
     */
    public function delete(string $refreshToken): void;
}
