<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\UserAggregate;

use Assert\Assert;
use FC\Domain\ValueObject\StringValue;

final class UserEmail extends StringValue
{
    /**
     * {@inheritDoc}
     */
    public static function fromString(string $value): static
    {
        $value = \strtolower($value);

        return parent::fromString($value);
    }

    /**
     * {@inheritDoc}
     */
    protected function assert(string $value): void
    {
        Assert::that($value)->notEmpty()->email();
    }
}
