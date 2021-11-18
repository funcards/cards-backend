<?php

declare(strict_types=1);

namespace FC\Application\Auth\JWT\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class ExpiredTokenException extends AuthenticationException implements JWTException
{
    /**
     * @param \Throwable $previous
     * @return static
     */
    #[Pure]
    public static function new(\Throwable $previous): self
    {
        return new self('Expired jwt token', 401, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Expired jwt token';
    }
}
