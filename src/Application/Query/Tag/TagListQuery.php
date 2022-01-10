<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use FC\Application\Bus\Query\PaginatedQuery;

final class TagListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    public readonly array $tags;

    public function __construct(
        public readonly string $boardId,
        public readonly string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$tags,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->tags = $tags;
    }
}
