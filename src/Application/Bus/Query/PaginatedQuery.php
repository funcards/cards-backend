<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use Assert\Assert;

class PaginatedQuery implements Query
{
    public final const MAX_PAGE_INDEX = 1000;
    public final const MAX_PAGE_SIZE = 1000;

    public readonly int $pageSize;

    public function __construct(public readonly int $pageIndex = 0, int $pageSize = 0)
    {
        if ($pageSize < 1 || $pageSize > static::MAX_PAGE_SIZE) {
            $pageSize = static::MAX_PAGE_SIZE;
        }

        Assert::that($pageIndex)->range(0, static::MAX_PAGE_INDEX);
        Assert::that($pageSize)->range(1, static::MAX_PAGE_SIZE);

        $this->pageSize = $pageSize;
    }
}
