<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

abstract class IntValue implements \Stringable
{
    final public function __construct(protected int $value)
    {
        $this->assert($value);
    }

    final public static function zero(): static
    {
        return static::fromInt(0);
    }

    final public static function fromInt(int $value): static
    {
        return new static($value);
    }

    /**
     * {@inheritDoc}
     */
    #[Pure]
    final public function __toString(): string
    {
        return \sprintf('%d', $this->asInt());
    }

    final public function asInt(): int
    {
        return $this->value;
    }

    final public function isBiggerThan(IntValue $other): bool
    {
        return $this->value > $other->value;
    }

    final public function isEqualTo(?object $other): bool
    {
        return $other::class === $this::class && (string)$this === (string)$other;
    }

    protected function assert(int $value): void
    {
    }
}
