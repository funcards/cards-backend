<?php

declare(strict_types=1);

namespace FC\Domain\Event\Tag;

use FC\Domain\Event\DomainEvent;

final class TagColorWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $color
     */
    public function __construct(string $aggregateId, private string $color)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
