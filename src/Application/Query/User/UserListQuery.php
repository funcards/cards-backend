<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use FC\Application\Bus\Query\PaginatedQuery;

final class UserListQuery extends PaginatedQuery
{
    /**
     * @var array<string>
     */
    private array $users;

    /**
     * @var array<string>
     */
    private array $emails;

    /**
     * @param int $pageIndex
     * @param int $pageSize
     * @param array<string> $users
     * @param string ...$emails
     */
    public function __construct(int $pageIndex = 0, int $pageSize = 0, array $users = [], string ...$emails)
    {
        parent::__construct($pageIndex, $pageSize);

        $this->users = $users;
        $this->emails = $emails;
    }

    /**
     * @return string[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }
}
