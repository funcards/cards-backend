<?php

declare(strict_types=1);

namespace FC\Domain\Event\Card;

use FC\Domain\Event\DomainEvent;

final class CardCategoryWasChanged extends DomainEvent
{
    public function __construct(string $aggregateId, private string $boardId, private string $categoryId)
    {
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
}
