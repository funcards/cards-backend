<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use Assert\Assert;

class PaginatedQuery implements Query
{
    public const MAX_PAGE_SIZE = 1000;

    /**
     * @param int $pageIndex
     * @param int $pageSize
     */
    public function __construct(private int $pageIndex = 0, private int $pageSize = 0)
    {
        if ($this->pageIndex < 0) {
            $this->pageIndex = 0;
        }

        if ($this->pageSize < 1 || $this->pageSize > static::MAX_PAGE_SIZE) {
            $this->pageSize = static::MAX_PAGE_SIZE;
        }

        Assert::that($this->pageIndex)->range(0, 1000);
        Assert::that($this->pageSize)->range(1, static::MAX_PAGE_SIZE);
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
