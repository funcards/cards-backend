<?php

declare(strict_types=1);

namespace FC\Domain\Event\Tag;

use FC\Domain\Event\DomainEvent;

final class TagWasCreated extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        public readonly string $boardId,
        public readonly string $name,
        public readonly string $color,
    ) {
        parent::__construct($aggregateId);
    }
}
