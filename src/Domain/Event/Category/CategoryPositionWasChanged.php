<?php

declare(strict_types=1);

namespace FC\Domain\Event\Category;

use FC\Domain\Event\DomainEvent;

final class CategoryPositionWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param int $position
     */
    public function __construct(string $aggregateId, private int $position)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
