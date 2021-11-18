<?php

declare(strict_types=1);

namespace FC\Domain\Authorization;

use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\ValueObject\Role;

interface AuthorizationCheckerInterface
{
    /**
     * @param BoardId $boardId
     * @param UserId $userId
     * @param Role $role
     * @return bool
     */
    public function isGranted(BoardId $boardId, UserId $userId, Role $role): bool;
}
