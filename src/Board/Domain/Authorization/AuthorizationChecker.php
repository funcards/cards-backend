<?php

declare(strict_types=1);

namespace FC\Board\Domain\Authorization;

use FC\Board\Domain\BoardRepository;
use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Role;
use FC\Shared\Domain\ValueObject\User\UserId;

final class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @param BoardRepository $boardRepository
     */
    public function __construct(private BoardRepository $boardRepository)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function isGranted(BoardId $boardId, UserId $userId, Role $role): bool
    {
        try {
            return $this->boardRepository->get($boardId)->isGranted($userId, $role);
        } catch (NotFoundDomainException) {
            return false;
        }
    }
}
