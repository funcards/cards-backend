<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

class CardContentWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, public readonly string $boardId, public readonly string $content)
    {
        parent::__construct($aggregateId);
    }
}
