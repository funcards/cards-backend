<?php

declare(strict_types=1);

namespace FC\Application\Query\Category;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class CategoryListQueryHandler implements QueryHandler
{
    /**
     * @param Connection $connection
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private Connection $connection,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @param CategoryListQuery $query
     * @return PaginatedResponse
     * @throws Exception
     */
    public function __invoke(CategoryListQuery $query): PaginatedResponse
    {
        if (false === $this->authorizationChecker->isGranted($query->getBoardId(), $query->getUserId(), Role::boardView())) {
            throw AccessDeniedException::new();
        }

        $qb = $this->connection->createQueryBuilder()
            ->select('c.*')
            ->from('categories', 'c')
            ->where('c.board_id = :boardId')
            ->orderBy('c.position', 'ASC')
            ->setFirstResult($query->getPageIndex())
            ->setMaxResults($query->getPageSize())
            ->setParameter('boardId', $query->getBoardId());

        if (0 < \count($query->getCategories())) {
            $qb
                ->andWhere('c.id IN(:categories)')
                ->setParameter('categories', $query->getCategories(), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $data[] = new CategoryResponse($row['id'], $row['board_id'], $row['name'], (int)$row['position']);
        }

        $count = \count($data);

        if ($query->getPageSize() > 1) {
            $count = (int)$qb->select('COUNT(c.id)')->fetchOne();
        }

        return new PaginatedResponse($query->getPageIndex(), $query->getPageSize(), $count, $data);
    }
}
