<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserPasswordWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, string $occurredOn, private string $password)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    public function password(): string
    {
        return $this->password;
    }
}
