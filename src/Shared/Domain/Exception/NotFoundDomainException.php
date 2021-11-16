<?php

namespace FC\Shared\Domain\Exception;

use FC\Shared\Domain\ValueObject\Id;
use JetBrains\PhpStorm\Pure;

class NotFoundDomainException extends \RuntimeException implements DomainException
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
    public static function create(Id $id, ?\Throwable $previous = null): static
    {
        $parts = \explode('\\', $id::class);
        $name = \str_replace('Id', '', \end($parts));
        return new static(\sprintf(static::MESSAGE, $name, $id), 404, $previous);
    }
}
