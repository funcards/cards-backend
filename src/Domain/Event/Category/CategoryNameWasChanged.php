<?php

declare(strict_types=1);

namespace FC\Domain\Event\Category;

use FC\Domain\Event\DomainEvent;

final class CategoryNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, public readonly string $boardId, public readonly string $name)
    {
        parent::__construct($aggregateId);
    }
}
