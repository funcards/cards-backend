<?php

declare(strict_types=1);

namespace FC\User\Application\JWT;

use FC\User\Application\JWT\Exception\InvalidPayloadException;

final class JWTManager implements JWTManagerInterface
{
    /**
     * @param JWTEncoder $encoder
     * @param string $identifierName
     */
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
        return $payload[$this->identifierName] ?? throw InvalidPayloadException::create();
    }
}
