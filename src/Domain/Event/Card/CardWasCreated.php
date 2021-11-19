<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardWasCreated extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $categoryId
     * @param string $name
     * @param string $content
     * @param array<string> $tags
     */
    public function __construct(
        string $aggregateId,
        private string $boardId,
        private string $categoryId,
        private string $name,
        private string $content,
        private array $tags,
    ) {
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
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
