<?php

declare(strict_types=1);

namespace FC\Shared\Domain\Aggregate;

use FC\Shared\Domain\Entity;

interface AggregateRoot extends Entity
{
    /**
     * @return array<object>
     */
    public function releaseEvents(): array;
}
