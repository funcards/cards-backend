<?php

declare(strict_types=1);

namespace FC\Domain\Event\Category;

use FC\Domain\Event\DomainEvent;

final class CategoryWasCreated extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $name
     * @param int $position
     */
    public function __construct(
        string $aggregateId,
        private string $boardId,
        private string $name,
        private int $position,
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
