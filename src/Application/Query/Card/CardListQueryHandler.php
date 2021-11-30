<?php

declare(strict_types=1);

namespace FC\Application\Query\Card;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use FC\Application\Bus\Query\PaginatedResponse;
use FC\Application\Bus\Query\QueryHandler;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class CardListQueryHandler implements QueryHandler
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
     * @param CardListQuery $query
     * @return PaginatedResponse
     * @throws Exception
     */
    public function __invoke(CardListQuery $query): PaginatedResponse
    {
        if (false === $this->authorizationChecker->isGranted(
                $query->getBoardId(),
                $query->getUserId(),
                Role::cardView()
            )) {
            throw AccessDeniedException::new();
        }

        $qb = $this->connection->createQueryBuilder()
            ->select('c.*')
            ->from('cards', 'c')
            ->where('c.board_id = :boardId')
            ->orderBy('c.position', 'ASC')
            ->setFirstResult($query->getPageIndex())
            ->setMaxResults($query->getPageSize())
            ->setParameter('boardId', $query->getBoardId());

        if (0 < \count($query->getCategories())) {
            $qb
                ->andWhere('c.category_id IN(:categories)')
                ->setParameter('categories', $query->getCategories(), Connection::PARAM_STR_ARRAY);
        }

        if (0 < \count($query->getCards())) {
            $qb
                ->andWhere('c.id IN(:cards)')
                ->setParameter('cards', $query->getCards(), Connection::PARAM_STR_ARRAY);
        }

        $data = [];

        foreach ($qb->fetchAllAssociative() as $row) {
            $data[] = new CardResponse(
                $row['id'],
                $row['board_id'],
                $row['category_id'],
                $row['name'],
                $row['content'],
                (int)$row['position'],
                \json_decode($row['tags'], true),
            );
        }

        $count = \count($data);

        if ($query->getPageSize() > 1) {
            $count = (int)$qb->select('COUNT(c.id)')->fetchOne();
        }

        return new PaginatedResponse($query->getPageIndex(), $query->getPageSize(), $count, $data);
    }
}
