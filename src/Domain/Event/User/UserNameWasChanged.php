<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserNameWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param string $name
     */
    public function __construct(string $aggregateId, string $occurredOn, private string $name)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
