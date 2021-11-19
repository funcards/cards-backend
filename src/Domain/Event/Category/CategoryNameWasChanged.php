<?php

declare(strict_types=1);

namespace FC\Domain\Event\Category;

use FC\Domain\Event\DomainEvent;

final class CategoryNameWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $name
     */
    public function __construct(string $aggregateId, private string $name)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}