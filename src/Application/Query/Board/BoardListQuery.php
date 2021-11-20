<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\PaginatedQuery;

final class BoardListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $boardIds;

    /**
     * @param string $userId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string ...$boardIds
     */
    public function __construct(
        string $userId,
        int $pageIndex = 0,
        int $pageSize = self::MAX_PAGE_SIZE,
        string ...$boardIds,
    ) {
        parent::__construct($userId, $pageIndex, $pageSize);

        $this->boardIds = $boardIds;
    }

    /**
     * @return array<string>
     */
    public function getBoardIds(): array
    {
        return $this->boardIds;
    }
}
