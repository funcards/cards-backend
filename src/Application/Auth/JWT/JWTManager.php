<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT;

use FC\Application\Auth\JWT\Exception\InvalidPayloadException;

final class JWTManager implements JWTManagerInterface
{
    public function __construct(private JWTEncoder $encoder, private string $identifierName)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $identifier, array $payload = [], array $headers = []): string
    {
        return $this->encoder->encode([$this->identifierName => $identifier] + $payload, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function parse(string $jwtToken): array
    {
        return $this->encoder->decode($jwtToken);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(array $payload): string
    {
        return $payload[$this->identifierName] ?? throw InvalidPayloadException::new();
    }
}
