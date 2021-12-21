<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, private string $name)
    {
        parent::__construct($aggregateId);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
