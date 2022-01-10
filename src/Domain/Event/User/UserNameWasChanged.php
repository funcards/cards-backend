<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, string $occurredOn, public readonly string $name)
    {
        parent::__construct($aggregateId, $occurredOn);
    }
}
