<?php

declare(strict_types=1);

namespace FC\Application\Auth\RefreshToken\Exception;

use JetBrains\PhpStorm\Pure;

final class InvalidRefreshTokenException extends \RuntimeException implements RefreshTokenException
{
    #[Pure]
    public static function new(): self
    {
        return new self('Invalid refresh token', 400);
    }
}
