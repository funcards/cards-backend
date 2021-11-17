<?php

declare(strict_types=1);

namespace FC\User\Application\JWT\Exception;

use JetBrains\PhpStorm\Pure;

final class InvalidPayloadException extends \RuntimeException implements JWTException
{
    /**
     * @return static
     */
    #[Pure]
    public static function new(): self
    {
        return new self('Invalid jwt payload', 400);
    }
}
