<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardMemberWasChanged extends DomainEvent
{
    /**
     * @param array<string> $roles
     */
    public function __construct(string $aggregateId, public readonly string $userId, public readonly array $roles)
    {
        parent::__construct($aggregateId);
    }
}
