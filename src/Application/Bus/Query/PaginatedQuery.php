<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

class PaginatedQuery implements Query
{
    /**
     * @param string $userId
     * @param int $pageIndex
     * @param int $pageSize
     */
    public function __construct(private string $userId, private int $pageIndex, private int $pageSize)
    {
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getPageIndex(): int
    {
        return $this->pageIndex;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
