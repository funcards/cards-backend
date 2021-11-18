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
    /**
     * @param BoardRepository $boardRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private BoardRepository $boardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @param RemoveBoardCommand $command
     */
    public function __invoke(RemoveBoardCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::boardRemove())) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);

        $this->boardRepository->remove($board);
    }
}
