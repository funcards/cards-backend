<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardNameWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $name
     */
    public function __construct(string $aggregateId, private string $boardId, private string $name)
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
