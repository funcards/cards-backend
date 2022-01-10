<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class TagListQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly Connection $connection,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(TagListQuery $query): PaginatedResponse
    {
        if (!$this->authorizationChecker->isGranted($query->boardId, $query->userId, Role::TagView)) {
            throw AccessDeniedException::new();
        }

        $qb = $this->connection->createQueryBuilder()
            ->select('t.*')
            ->from('tags', 't')
            ->where('t.board_id = :boardId')
            ->setFirstResult($query->pageIndex)
            ->setMaxResults($query->pageSize)
            ->setParameter('boardId', $query->boardId);

        if ([] !== $query->tags) {
            $qb
                ->andWhere('t.id IN(:tags)')
                ->setParameter('tags', $query->tags, Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $data[] = new TagResponse($row['id'], $row['board_id'], $row['name'], $row['color']);
        }

        $count = \count($data);

        if ($query->pageSize > 1) {
            $count = (int)$qb->select('COUNT(t.id)')->fetchOne();
        }

        return new PaginatedResponse($query->pageIndex, $query->pageSize, $count, $data);
    }
}
