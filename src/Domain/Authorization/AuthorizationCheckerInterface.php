<?php

declare(strict_types=1);

namespace FC\Domain\Authorization;

use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\ValueObject\Role;

interface AuthorizationCheckerInterface
{
    /**
     * @param BoardId|string $boardId
     * @param UserId|string $userId
     * @param Role $role
     * @return bool
     */
    public function isGranted(BoardId|string $boardId, UserId|string $userId, Role $role): bool;
}
