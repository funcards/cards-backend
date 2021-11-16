<?php

declare(strict_types=1);

namespace FC\Board\Domain\Event;

use FC\Shared\Domain\Event\DomainEvent;

final class BoardDescriptionWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string|null $description
     */
    public function __construct(string $aggregateId, private ?string $description)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
