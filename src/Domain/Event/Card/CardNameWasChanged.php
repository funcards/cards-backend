<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, private string $boardId, private string $name)
    {
        parent::__construct($aggregateId);
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
