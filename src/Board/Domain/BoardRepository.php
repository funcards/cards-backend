<?php

declare(strict_types=1);

namespace FC\Board\Domain;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Board\BoardId;

interface BoardRepository
{
    /**
     * @param BoardId $id
     * @return Board
     * @throws NotFoundDomainException
     */
    public function get(BoardId $id): Board;

    /**
     * @param Board $board
     */
    public function save(Board $board): void;
}
