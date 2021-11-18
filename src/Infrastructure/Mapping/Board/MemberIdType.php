<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\Board;

use FC\Domain\Aggregate\BoardAggregate\MemberId;
use FC\Infrastructure\Mapping\IdType;

final class MemberIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return MemberId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'member_id';
    }
}
