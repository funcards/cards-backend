<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate;

use FC\Domain\Event\DomainEvent;

trait EventRecording
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @param DomainEvent $event
     */
    final protected function recordThat(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    final public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
