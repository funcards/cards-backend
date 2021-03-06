<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use FC\Domain\ValueObject\StringValue;

final class CardContent extends StringValue
{
    protected function assert(string $value): void
    {
    }
}
