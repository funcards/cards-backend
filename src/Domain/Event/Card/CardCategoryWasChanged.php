<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardCategoryWasChanged extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $boardId
     * @param string $categoryId
     */
    public function __construct(string $aggregateId, private string $boardId, private string $categoryId)
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
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
}
