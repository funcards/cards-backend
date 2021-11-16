<?php

declare(strict_types=1);

namespace FC\User\Application\JWT\Exception;

use JetBrains\PhpStorm\Pure;

final class InvalidTokenException extends \RuntimeException implements JWTException
{
    /**
     * @param \Throwable $previous
     * @return static
     */
    #[Pure]
    public static function create(\Throwable $previous): self
    {
        return new self('Invalid token', 400, $previous);
    }
}
