<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardCategoryWasChanged extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        public readonly string $boardId,
        public readonly string $categoryId,
    ) {
        parent::__construct($aggregateId);
    }
}
