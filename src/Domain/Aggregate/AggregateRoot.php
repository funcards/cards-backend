<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate;

interface AggregateRoot extends Entity
{
    /**
     * @return array<object>
     */
    public function releaseEvents(): array;
}
