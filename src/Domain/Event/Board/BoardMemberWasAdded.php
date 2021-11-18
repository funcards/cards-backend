<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardMemberWasAdded extends DomainEvent
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
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
