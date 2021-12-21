<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class InvalidTokenException extends AuthenticationException implements JWTException
{
    #[Pure]
    public static function new(\Throwable $previous): self
    {
        return new self('Invalid jwt token.', 400, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Invalid jwt token';
    }
}
