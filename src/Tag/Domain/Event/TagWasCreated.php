<?php

declare(strict_types=1);

namespace FC\Tag\Domain\Event;

use FC\Shared\Domain\Event\DomainEvent;

final class TagWasCreated extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $name
     * @param string $color
     */
    public function __construct(
        string $aggregateId,
        private string $boardId,
        private string $name,
        private string $color,
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
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
