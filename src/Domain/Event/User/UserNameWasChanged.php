<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserNameWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, string $occurredOn, private string $name)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
