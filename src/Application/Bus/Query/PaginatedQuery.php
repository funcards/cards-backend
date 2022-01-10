<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use Assert\Assert;

class PaginatedQuery implements Query
{
    public final const MAX_PAGE_INDEX = 1000;
    public final const MAX_PAGE_SIZE = 1000;

    public function __construct(public readonly int $pageIndex = 0, public readonly int $pageSize = 0)
    {
        Assert::that($pageIndex)->range(0, static::MAX_PAGE_INDEX);
        Assert::that($pageSize)->range(1, static::MAX_PAGE_SIZE);
    }
}
