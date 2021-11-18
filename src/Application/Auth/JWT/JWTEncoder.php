<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT;

use FC\Application\Auth\JWT\Exception\ExpiredTokenException;
use FC\Application\Auth\JWT\Exception\InvalidTokenException;

interface JWTEncoder
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $headers
     * @return string
     * @throws \Throwable
     */
    public function encode(array $payload, array $headers = []): string;

    /**
     * @param string $token
     * @return array<string, mixed> return payload
     * @throws InvalidTokenException
     * @throws ExpiredTokenException
     */
    public function decode(string $token): array;
}
