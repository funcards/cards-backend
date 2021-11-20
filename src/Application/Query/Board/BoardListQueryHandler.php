<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;

final class BoardListQueryHandler implements QueryHandler
{
    /**
     * @param Connection $connection
     */
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @param BoardListQuery $query
     * @return PaginatedResponse
     * @throws Exception
     */
    public function __invoke(BoardListQuery $query): PaginatedResponse
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('b.*', 'STRING_AGG(m.user_id::TEXT, \'|\' ORDER BY m.user_id) as users', 'STRING_AGG(m.roles::TEXT, \'|\'  ORDER BY m.user_id) as roles')
            ->from('boards', 'b')
            ->leftJoin('b', 'board_members', 'm', 'm.board_id = b.id')
            ->where('m.user_id = :userId')
            ->groupBy('b.id')
            ->setFirstResult($query->getPageIndex())
            ->setParameter('userId', $query->getUserId());

        if (\PHP_INT_MAX !== $query->getPageSize()) {
            $qb->setMaxResults($query->getPageSize());
        }

        if (0 < \count($query->getBoardIds())) {
            $qb->andWhere('b.id IN(:boardIds)')
                ->setParameter('boardIds', $query->getBoardIds(), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $members = [];

            $users = \explode('|', $row['users']);
            $roles = \explode('|', $row['roles']);

            foreach ($users as $i => $user) {
                $members[] = new MemberResponse($user, \json_decode($roles[$i], true));
            }

            $data[] = new BoardResponse(
                $row['id'],
                $row['name'],
                $row['color'],
                $row['description'],
                $row['created_at'],
                $members,
            );
        }

        $count = \count($data);

        if ($query->getPageSize() > 1 && $query->getPageSize() === $count) {
            $count = (int)$qb->select('count(b.id)')->fetchOne();
        }

        return new PaginatedResponse($query->getPageIndex(), $query->getPageSize(), $count, $data);
    }
}
