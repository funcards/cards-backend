<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use FC\Application\Bus\Query\PaginatedQuery;

final class UserListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    public readonly array $emails;

    /**
     * @param array<string> $users
     */
    public function __construct(
        int $pageIndex = 0,
        int $pageSize = 0,
        public readonly array $users = [],
        string ...$emails,
    ) {
        parent::__construct($pageIndex, $pageSize);

        $this->emails = $emails;
    }
}
