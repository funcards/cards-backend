<?php

declare(strict_types=1);

namespace FC\User\Domain\Event;

use FC\Shared\Domain\Event\DomainEvent;

final class UserEmailWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param string $email
     */
    public function __construct(string $aggregateId, string $occurredOn, private string $email)
    {
        parent::__construct($aggregateId, $occurredOn);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
