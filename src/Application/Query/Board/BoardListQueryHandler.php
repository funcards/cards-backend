<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;

final class BoardListQueryHandler implements QueryHandler
{
    private const SEPARATOR = '<|>';

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(BoardListQuery $query): PaginatedResponse
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'b.*',
                \sprintf('STRING_AGG(u.id::TEXT, \'%s\' ORDER BY u.id) as user_ids', self::SEPARATOR),
                \sprintf('STRING_AGG(u.name::TEXT, \'%s\' ORDER BY u.id) as user_names', self::SEPARATOR),
                \sprintf('STRING_AGG(u.email::TEXT, \'%s\' ORDER BY u.id) as user_emails', self::SEPARATOR),
                \sprintf('STRING_AGG(m.roles::TEXT, \'%s\'  ORDER BY u.id) as member_roles', self::SEPARATOR),
            )
            ->from('boards', 'b')
            ->leftJoin('b', 'board_members', 'm', 'm.board_id = b.id')
            ->leftJoin('b', 'users', 'u', 'u.id = m.user_id')
            ->where('m.user_id = :userId')
            ->groupBy('b.id')
            ->setFirstResult($query->getPageIndex())
            ->setMaxResults($query->getPageSize())
            ->setParameter('userId', $query->getUserId());

        if ([] !== $query->getBoards()) {
            $qb
                ->andWhere('b.id IN(:boards)')
                ->setParameter('boards', $query->getBoards(), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $members = [];

            $ids = \explode(self::SEPARATOR, $row['user_ids']);
            $names = \explode(self::SEPARATOR, $row['user_names']);
            $emails = \explode(self::SEPARATOR, $row['user_emails']);
            $roles = \explode(self::SEPARATOR, $row['member_roles']);

            foreach ($ids as $i => $userId) {
                $members[$userId] = new MemberResponse($userId, $names[$i], $emails[$i], \json_decode($roles[$i], true, 512, JSON_THROW_ON_ERROR));
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

        if ($query->getPageSize() > 1) {
            $count = (int)$qb->select('COUNT(b.id)')->fetchOne();
        }

        return new PaginatedResponse($query->getPageIndex(), $query->getPageSize(), $count, $data);
    }
}
