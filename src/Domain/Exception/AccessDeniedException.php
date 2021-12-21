<?php

declare(strict_types=1);

namespace FC\Domain\Exception;

use JetBrains\PhpStorm\Pure;

final class AccessDeniedException extends \RuntimeException implements DomainException
{
    #[Pure]
    public static function new(?\Throwable $previous = null): self
    {
        return new self('Access Denied.', 403, $previous);
    }
}
