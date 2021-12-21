<?php

declare(strict_types=1);

namespace FC\Domain\Event\Tag;

use FC\Domain\Event\DomainEvent;

final class TagWasCreated extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private string $boardId,
        private string $name,
        private string $color,
    ) {
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

    public function getColor(): string
    {
        return $this->color;
    }
}
