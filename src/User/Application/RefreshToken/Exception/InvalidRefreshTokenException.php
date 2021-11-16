<?php

declare(strict_types=1);

namespace FC\User\Application\RefreshToken\Exception;

use JetBrains\PhpStorm\Pure;

final class InvalidRefreshTokenException extends \RuntimeException implements RefreshTokenException
{
    /**
     * @return static
     */
    #[Pure]
    public static function create(): self
    {
        return new self('Invalid refresh token', 400);
    }
}
