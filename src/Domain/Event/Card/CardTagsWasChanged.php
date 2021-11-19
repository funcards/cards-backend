<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardTagsWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param array<string> $tags
     */
    public function __construct(string $aggregateId, private string $boardId, private array $tags)
    {
        parent::__construct($aggregateId);
    }

    /**
     * @return string
     */
    public function getBoardId(): string
    {
        return $this->boardId;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}