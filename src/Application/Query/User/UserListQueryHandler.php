<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;

final class UserListQueryHandler implements QueryHandler
{
    /**
     * @param Connection $connection
     */
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @param UserListQuery $query
     * @return PaginatedResponse
     * @throws Exception
     */
    public function __invoke(UserListQuery $query): PaginatedResponse
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.id', 'u.name', 'u.email')
            ->from('users', 'u')
            ->setFirstResult($query->getPageIndex())
            ->setMaxResults($query->getPageSize());

        if (0 < \count($query->getUsers())) {
            $qb
                ->andWhere('u.id IN(:users)')
                ->setParameter('users', $query->getUsers(), Connection::PARAM_STR_ARRAY);
        }

        if (0 < \count($query->getEmails())) {
            $qb
                ->andWhere('u.email IN(:emails)')
                ->setParameter('emails', \array_map('strtolower', $query->getEmails()), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $data[] = new UserResponse($row['id'], $row['name'], $row['email']);
        }

        $count = \count($data);

        if ($query->getPageSize() > 1) {
            $count = (int)$qb->select('COUNT(u.id)')->fetchOne();
        }

        return new PaginatedResponse($query->getPageIndex(), $query->getPageSize(), $count, $data);
    }
}
