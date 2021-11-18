<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserRolesWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param array<string> $roles
     */
    public function __construct(string $aggregateId, string $occurredOn, private array $roles)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
