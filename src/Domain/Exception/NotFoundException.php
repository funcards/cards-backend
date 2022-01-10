<?php

declare(strict_types=1);

namespace FC\Domain\Exception;

use FC\Domain\ValueObject\Id;
use JetBrains\PhpStorm\Pure;

class NotFoundException extends \RuntimeException implements DomainException
{
    protected const MESSAGE = '%s "%s" not found.';

    #[Pure]
    final public function __construct(string $message = '', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function new(Id $id, ?\Throwable $previous = null): static
    {
        $parts = \explode('\\', $id::class);
        $name = \str_replace('Id', '', \end($parts));
        return new static(\sprintf(static::MESSAGE, $name, $id), 404, $previous);
    }
}
