<?php

declare(strict_types=1);

namespace FC\Shared\Domain\ValueObject;

use Assert\Assert;

final class Role extends StringValue
{
    /**
     * {@inheritDoc}
     */
    protected function assert(string $value): void
    {
        Assert::that($value)->notEmpty()->startsWith('ROLE_');
    }
}
