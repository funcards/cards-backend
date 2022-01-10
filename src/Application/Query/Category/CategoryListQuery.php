<?php

declare(strict_types=1);

namespace FC\Application\Query\Category;

use FC\Application\Bus\Query\PaginatedQuery;

final class CategoryListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    public readonly array $categories;

    public function __construct(
        public readonly string $boardId,
        public readonly string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$categories,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->categories = $categories;
    }
}
