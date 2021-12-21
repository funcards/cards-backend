<?php

declare(strict_types=1);

namespace FC\Domain\Authorization;

use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\ValueObject\Role;

interface AuthorizationCheckerInterface
{
    public function isGranted(BoardId|string $boardId, UserId|string $userId, Role $role): bool;
}
