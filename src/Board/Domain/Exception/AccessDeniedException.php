<?php

declare(strict_types=1);

namespace FC\Board\Domain\Exception;

use JetBrains\PhpStorm\Pure;

final class AccessDeniedException extends \RuntimeException implements BoardException
{
    /**
     * @param \Throwable|null $previous
     * @return static
     */
    #[Pure]
    public static function new(?\Throwable $previous = null): self
    {
        return new self('Access Denied.', 403, $previous);
    }
}
