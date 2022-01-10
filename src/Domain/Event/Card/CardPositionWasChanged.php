<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardPositionWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, public readonly string $boardId, public readonly int $position)
    {
        parent::__construct($aggregateId);
    }
}
