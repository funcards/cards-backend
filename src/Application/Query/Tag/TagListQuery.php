<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use FC\Application\Bus\Query\PaginatedQuery;

final class TagListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $tags;

    public function __construct(
        private string $boardId,
        private string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$tags,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->tags = $tags;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
