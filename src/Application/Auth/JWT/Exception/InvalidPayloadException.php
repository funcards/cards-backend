<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class InvalidPayloadException extends AuthenticationException implements JWTException
{
    #[Pure]
    public static function new(): self
    {
        return new self('Invalid jwt payload', 400);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Invalid jwt payload';
    }
}
