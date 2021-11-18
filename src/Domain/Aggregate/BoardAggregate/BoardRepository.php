<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\BoardAggregate;

use FC\Domain\Exception\NotFoundException;

interface BoardRepository
{
    /**
     * @param BoardId $id
     * @return Board
     * @throws NotFoundException
     */
    public function get(BoardId $id): Board;

    /**
     * @param Board $board
     */
    public function save(Board $board): void;

    /**
     * @param Board $board
     */
    public function remove(Board $board): void;
}
