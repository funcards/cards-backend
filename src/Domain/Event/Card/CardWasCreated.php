<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardWasCreated extends DomainEvent
{
    /**
     * @param array<string> $tags
     */
    public function __construct(
        string $aggregateId,
        public readonly string $boardId,
        public readonly string $categoryId,
        public readonly string $name,
        public readonly string $content,
        public readonly int $position,
        public readonly array $tags,
    ) {
        parent::__construct($aggregateId);
    }
}
