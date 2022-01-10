<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use FC\Domain\Aggregate\BoardAggregate\Board;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;

final class DoctrineBoardRepository extends DoctrineRepository implements BoardRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(BoardId $id): Board
    {
        return $this->find(Board::class, $id);
    }

    public function save(Board $board): void
    {
        $this->persist($board);
    }

    public function remove(Board $board): void
    {
        $this->delete($board);
    }
}
