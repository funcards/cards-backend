<?php

declare(strict_types=1);

namespace FC\Application\Query\Card;

use FC\Application\Bus\Query\PaginatedQuery;

final class CardListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    public readonly array $cards;

    /**
     * @param array<string> $categories
     */
    public function __construct(
        public readonly string $boardId,
        public readonly string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        public readonly array $categories = [],
        string ...$cards,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->cards = $cards;
    }
}
