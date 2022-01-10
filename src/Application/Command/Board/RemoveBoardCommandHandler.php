<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\CommandHandler;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class RemoveBoardCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function __invoke(RemoveBoardCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::BoardOwner)) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);

        $this->boardRepository->remove($board);
    }
}
