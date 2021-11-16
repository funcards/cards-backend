<?php

declare(strict_types=1);

namespace FC\Shared\Domain\Event;

interface EventBus
{
    /**
     * @param DomainEvent ...$events
     */
    public function publish(DomainEvent ...$events): void;
}
