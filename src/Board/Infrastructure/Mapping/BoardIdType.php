<?php

declare(strict_types=1);

namespace FC\Board\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Infrastructure\Mapping\IdType;

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
