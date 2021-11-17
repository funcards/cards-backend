<?php

declare(strict_types=1);

namespace FC\User\Domain\Exception;

use JetBrains\PhpStorm\Pure;

final class BadCredentialsException extends \RuntimeException implements UserException
{
    /**
     * @param string $message
     * @param \Throwable|null $previous
     * @return static
     */
    #[Pure]
    public static function new(string $message, ?\Throwable $previous = null): self
    {
        return new self($message, 400, $previous);
    }
}
