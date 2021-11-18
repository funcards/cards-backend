<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardDescriptionWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $description
     */
    public function __construct(string $aggregateId, private string $description)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
