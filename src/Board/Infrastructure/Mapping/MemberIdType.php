<?php

declare(strict_types=1);

namespace FC\Board\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\Board\MemberId;
use FC\Shared\Infrastructure\Mapping\IdType;

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
