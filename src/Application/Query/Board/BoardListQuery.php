<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\PaginatedQuery;

final class BoardListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    public readonly array $boards;

    public function __construct(
        public readonly string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$boards,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->boards = $boards;
    }
}
