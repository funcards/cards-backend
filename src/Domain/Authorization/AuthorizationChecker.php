<?php

declare(strict_types=1);

namespace FC\Domain\Authorization;

use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Aggregate\UserAggregate\UserRepository;
use FC\Domain\Exception\NotFoundException;
use FC\Domain\ValueObject\Role;

final class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @param UserRepository $userRepository
     * @param BoardRepository $boardRepository
     */
    public function __construct(private UserRepository $userRepository, private BoardRepository $boardRepository)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function isGranted(BoardId|string $boardId, UserId|string $userId, Role $role): bool
    {
        if (\is_string($boardId)) {
            $boardId = BoardId::fromString($boardId);
        }

        if (\is_string($userId)) {
            $userId = UserId::fromString($userId);
        }

        try {
            if ($this->userRepository->get($userId)->isGranted(Role::superAdmin())) {
                return true;
            }

            return $this->boardRepository->get($boardId)->isGranted($userId, $role);
        } catch (NotFoundException) {
            return false;
        }
    }
}
