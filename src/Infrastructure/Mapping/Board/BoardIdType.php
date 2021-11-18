<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\Board;

use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Infrastructure\Mapping\IdType;

final class BoardIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return BoardId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'board_id';
    }
}
