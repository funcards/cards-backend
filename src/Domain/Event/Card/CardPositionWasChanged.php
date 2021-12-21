<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardPositionWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, private string $boardId, private int $position)
    {
        parent::__construct($aggregateId);
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
