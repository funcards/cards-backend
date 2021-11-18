<?php

declare(strict_types=1);

namespace FC\Application\Bus\Event;

use FC\Domain\Event\DomainEvent;

interface EventBus
{
    /**
     * @param DomainEvent ...$events
     */
    public function publish(DomainEvent ...$events): void;
}
