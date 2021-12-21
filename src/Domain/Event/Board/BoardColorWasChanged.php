<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardColorWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, private string $color)
    {
        parent::__construct($aggregateId);
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
