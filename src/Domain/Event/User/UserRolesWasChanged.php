<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserRolesWasChanged extends DomainEvent
{
    /**
     * @param array<string> $roles
     */
    public function __construct(string $aggregateId, string $occurredOn, public readonly array $roles)
    {
        parent::__construct($aggregateId, $occurredOn);
    }
}
