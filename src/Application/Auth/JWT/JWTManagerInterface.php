<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT;

use FC\Application\Auth\JWT\Exception\ExpiredTokenException;
use FC\Application\Auth\JWT\Exception\InvalidPayloadException;
use FC\Application\Auth\JWT\Exception\InvalidTokenException;

interface JWTManagerInterface
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $headers
     * @throws \Throwable
     */
    public function create(string $identifier, array $payload = [], array $headers = []): string;

    /**
     * @return array<string, mixed>
     * @throws InvalidTokenException
     * @throws ExpiredTokenException
     */
    public function parse(string $jwtToken): array;

    /**
     * @param array<string, mixed> $payload
     * @throws InvalidPayloadException
     */
    public function getIdentifier(array $payload): string;
}
