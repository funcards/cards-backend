<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserPasswordWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param string $password
     */
    public function __construct(string $aggregateId, string $occurredOn, private string $password)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }
}