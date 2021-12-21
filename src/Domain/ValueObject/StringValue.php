<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;

abstract class StringValue implements \Stringable
{
    final public function __construct(protected string $value)
    {
        $this->assert($value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    final public function asString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    #[Pure]
    final public function __toString(): string
    {
        return $this->asString();
    }

    final public function isEqualTo(?object $other): bool
    {
        return $other::class === $this::class && (string)$this === (string)$other;
    }

    protected function assert(string $value): void
    {
        Assert::that(\trim($value))->notEmpty();
    }
}
