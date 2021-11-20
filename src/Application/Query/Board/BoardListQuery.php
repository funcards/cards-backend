<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\PaginatedQuery;

final class BoardListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $boards;

    /**
     * @param string $userId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string ...$boards
     */
    public function __construct(string $userId, int $pageIndex = 0, int $pageSize = 0, string ...$boards)
    {
        parent::__construct($userId, $pageIndex, $pageSize);

        $this->boards = $boards;
    }

    /**
     * @return array<string>
     */
    public function getBoards(): array
    {
        return $this->boards;
    }
}
