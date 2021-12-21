<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\BoardAggregate;

use FC\Domain\Exception\NotFoundException;

interface BoardRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(BoardId $id): Board;

    public function save(Board $board): void;

    public function remove(Board $board): void;
}
