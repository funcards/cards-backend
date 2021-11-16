<?php

namespace FC\Board\Domain;

use Assert\Assert;
use FC\Shared\Domain\ValueObject\StringValue;

final class BoardDescription extends StringValue
{
    /**
     * {@inheritDoc}
     */
    protected function assert(string $value): void
    {
        Assert::thatNullOr($value)->notEmpty();
    }
}
