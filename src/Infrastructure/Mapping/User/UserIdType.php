<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\User;

use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Infrastructure\Mapping\IdType;

final class UserIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return UserId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'user_id';
    }
}
