<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;

final class UserListQueryHandler implements QueryHandler
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(UserListQuery $query): PaginatedResponse
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.id', 'u.name', 'u.email')
            ->from('users', 'u')
            ->setFirstResult($query->pageIndex)
            ->setMaxResults($query->pageSize);

        if ([] !== $query->users) {
            $qb
                ->andWhere('u.id IN(:users)')
                ->setParameter('users', $query->users, Connection::PARAM_STR_ARRAY);
        }

        if ([] !== $query->emails) {
            $qb
                ->andWhere('u.email IN(:emails)')
                ->setParameter('emails', \array_map('strtolower', $query->emails), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $data[] = new UserResponse($row['id'], $row['name'], $row['email']);
        }

        $count = \count($data);

        if ($query->pageSize > 1) {
            $count = (int)$qb->select('COUNT(u.id)')->fetchOne();
        }

        return new PaginatedResponse($query->pageIndex, $query->pageSize, $count, $data);
    }
}
