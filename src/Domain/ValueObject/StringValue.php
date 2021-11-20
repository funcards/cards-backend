<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;

abstract class StringValue implements \Stringable
{
    /**
     * @param string $value
     */
    final public function __construct(protected string $value)
    {
        $this->assert($value);
    }

    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    /**
     * @return string
     */
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

    /**
     * @param object|null $other
     * @return bool
     */
    final public function isEqualTo(?object $other): bool
    {
        return \get_class($other) === \get_class($this) && (string)$this === (string)$other;
    }

    /**
     * @param string $value
     */
    protected function assert(string $value): void
    {
        Assert::that(\trim($value))->notEmpty();
    }
}
