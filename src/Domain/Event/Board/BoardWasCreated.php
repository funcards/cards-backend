<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardWasCreated extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        string $occurredOn,
        public readonly string $ownerId,
        public readonly string $name,
        public readonly string $color,
        public readonly ?string $description,
    ) {
        parent::__construct($aggregateId, $occurredOn);
    }
}
