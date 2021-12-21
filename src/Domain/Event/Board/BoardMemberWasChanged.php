<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardMemberWasChanged extends DomainEvent
{
    /**
     * @param array<string> $roles
     */
    public function __construct(string $aggregateId, private string $userId, private array $roles)
    {
        parent::__construct($aggregateId);
    }

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
