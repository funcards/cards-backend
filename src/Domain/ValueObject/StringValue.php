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
    final public static function fromString(string $value): static
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
     * @param string $value
     */
    protected function assert(string $value): void
    {
        Assert::that(\trim($value))->notEmpty();
    }
}
