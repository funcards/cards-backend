<?php

namespace FC\Domain\Exception;

use FC\Domain\ValueObject\Id;
use JetBrains\PhpStorm\Pure;

class NotFoundException extends \RuntimeException implements DomainException
{
    protected const MESSAGE = '%s "%s" not found.';

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    #[Pure]
    final public function __construct(string $message = '', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param Id $id
     * @param \Throwable|null $previous
     * @return static
     */
    public static function new(Id $id, ?\Throwable $previous = null): static
    {
        $parts = \explode('\\', $id::class);
        $name = \str_replace('Id', '', \end($parts));
        return new static(\sprintf(static::MESSAGE, $name, $id), 404, $previous);
    }
}
