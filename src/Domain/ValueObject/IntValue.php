<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

abstract class IntValue implements \Stringable
{
    /**
     * @param int $value
     */
    final public function __construct(protected int $value)
    {
        $this->assert($value);
    }

    /**
     * @return static
     */
    final public static function zero(): static
    {
        return static::fromInt(0);
    }

    /**
     * @param int $value
     * @return static
     */
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

    /**
     * @return int
     */
    final public function asInt(): int
    {
        return $this->value;
    }

    /**
     * @param IntValue $other
     * @return bool
     */
    final public function isBiggerThan(IntValue $other): bool
    {
        return $this->value > $other->value;
    }

    /**
     * @param int $value
     */
    protected function assert(int $value): void
    {
    }
}
