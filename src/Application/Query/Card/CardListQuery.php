<?php

declare(strict_types=1);

namespace FC\Application\Query\Card;

use FC\Application\Bus\Query\PaginatedQuery;

final class CardListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $categories;

    /**
     * @var array<string>
     */
    private array $cards;

    /**
     * @param array<string> $categories
     */
    public function __construct(
        private string $boardId,
        private string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        array $categories = [],
        string ...$cards,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->categories = $categories;
        $this->cards = $cards;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return string[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
