<?php

declare(strict_types=1);

namespace FC\User\Domain;

use Assert\Assert;
use FC\Shared\Domain\ValueObject\StringValue;

final class UserPassword extends StringValue
{
    /**
     * {@inheritDoc}
     */
    protected function assert(string $value): void
    {
        Assert::that($value)->notEmpty();
    }
}
