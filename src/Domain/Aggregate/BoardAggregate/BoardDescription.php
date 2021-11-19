<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\BoardAggregate;

use FC\Domain\ValueObject\StringValue;

final class BoardDescription extends StringValue
{
    /**
     * {@inheritDoc}
     */
    protected function assert(string $value): void
    {
    }
}
