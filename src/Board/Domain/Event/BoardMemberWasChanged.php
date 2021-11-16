<?php

declare(strict_types=1);

namespace FC\Board\Domain\Event;

use FC\Shared\Domain\Event\DomainEvent;

final class BoardMemberWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $userId
     * @param array<string> $roles
     */
    public function __construct(string $aggregateId, private string $userId, private array $roles)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
