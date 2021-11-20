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

    /**
     * @param string $boardId
     * @param string $userId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string ...$tags
     */
    public function __construct(
        private string $boardId,
        string $userId,
        int $pageIndex = 0,
        int $pageSize = 0,
        string ...$tags,
    ) {
        parent::__construct($userId, $pageIndex, $pageSize);

        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getBoardId(): string
    {
        return $this->boardId;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
