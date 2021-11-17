<?php

declare(strict_types=1);

namespace FC\Board\Domain\Authorization;

use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Role;
use FC\Shared\Domain\ValueObject\User\UserId;

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
