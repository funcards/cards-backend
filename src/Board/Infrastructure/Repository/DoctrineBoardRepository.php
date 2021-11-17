<?php

declare(strict_types=1);

namespace FC\Board\Infrastructure\Repository;

use FC\Board\Domain\Board;
use FC\Board\Domain\BoardRepository;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Infrastructure\Repository\DoctrineRepository;

final class DoctrineBoardRepository extends DoctrineRepository implements BoardRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(BoardId $id): Board
    {
        return $this->find(Board::class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(Board $board): void
    {
        $this->persist($board);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Board $board): void
    {
        $this->delete($board);
    }
}
