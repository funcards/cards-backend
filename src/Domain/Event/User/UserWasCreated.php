<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserWasCreated extends DomainEvent
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        string $aggregateId,
        string $occurredOn,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly array $roles,
    ) {
        parent::__construct($aggregateId, $occurredOn);
    }
}
