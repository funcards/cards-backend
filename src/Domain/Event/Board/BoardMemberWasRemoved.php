<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardMemberWasRemoved extends DomainEvent
{
    public function __construct(string $aggregateId, private string $userId)
    {
        parent::__construct($aggregateId);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
