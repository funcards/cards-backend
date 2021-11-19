<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

class CardContentWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $content
     */
    public function __construct(string $aggregateId, private string $boardId, private string $content)
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
    public function getContent(): string
    {
        return $this->content;
    }
}
