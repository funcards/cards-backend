<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, public readonly string $name)
    {
        parent::__construct($aggregateId);
    }
}
