<?php

declare(strict_types=1);

namespace FC\Application\Query\Category;

use FC\Application\Bus\Query\PaginatedQuery;

final class CategoryListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $categories;

    /**
     * @param string $boardId
     * @param string $userId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string ...$categories
     */
    public function __construct(
        private string $boardId,
        private string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$categories,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->categories = $categories;
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
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
