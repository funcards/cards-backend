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
        private string $boardId,
        private string $categoryId,
        private string $name,
        private string $content,
        private int $position,
        private array $tags,
    ) {
        parent::__construct($aggregateId);
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
