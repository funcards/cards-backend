<?php

declare(strict_types=1);

namespace FC\User\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\User\UserId;
use FC\Shared\Infrastructure\Mapping\IdType;

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
