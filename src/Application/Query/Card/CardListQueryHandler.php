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
    public function __construct(
        private readonly Connection $connection,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CardListQuery $query): PaginatedResponse
    {
        if (!$this->authorizationChecker->isGranted(
                $query->boardId,
                $query->userId,
                Role::CardView,
            )) {
            throw AccessDeniedException::new();
        }

        $qb = $this->connection->createQueryBuilder()
            ->select('c.*')
            ->from('cards', 'c')
            ->where('c.board_id = :boardId')
            ->orderBy('c.position', 'ASC')
            ->setFirstResult($query->pageIndex)
            ->setMaxResults($query->pageSize)
            ->setParameter('boardId', $query->boardId);

        if ([] !== $query->categories) {
            $qb
                ->andWhere('c.category_id IN(:categories)')
                ->setParameter('categories', $query->categories, Connection::PARAM_STR_ARRAY);
        }

        if ([] !== $query->cards) {
            $qb
                ->andWhere('c.id IN(:cards)')
                ->setParameter('cards', $query->cards, Connection::PARAM_STR_ARRAY);
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
                \json_decode($row['tags'], true, 512, JSON_THROW_ON_ERROR),
            );
        }

        $count = \count($data);

        if ($query->pageSize > 1) {
            $count = (int)$qb->select('COUNT(c.id)')->resetQueryPart('orderBy')->fetchOne();
        }

        return new PaginatedResponse($query->pageIndex, $query->pageSize, $count, $data);
    }
}
