<?php

declare(strict_types=1);

namespace FC\User\Application\JWT;

use FC\User\Application\JWT\Exception\ExpiredTokenException;
use FC\User\Application\JWT\Exception\InvalidPayloadException;
use FC\User\Application\JWT\Exception\InvalidTokenException;

interface JWTManagerInterface
{
    /**
     * @param string $identifier
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $headers
     * @return string
     * @throws \Throwable
     */
    public function create(string $identifier, array $payload = [], array $headers = []): string;

    /**
     * @param string $jwtToken
     * @return array<string, mixed>
     * @throws InvalidTokenException
     * @throws ExpiredTokenException
     */
    public function parse(string $jwtToken): array;

    /**
     * @param array<string, mixed> $payload
     * @return string
     * @throws InvalidPayloadException
     */
    public function getIdentifier(array $payload): string;
}
