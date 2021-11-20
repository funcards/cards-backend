<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class RemoveMemberCommandHandler implements CommandHandler
{
    /**
     * @param BoardRepository $boardRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EventBus $eventBus
     */
    public function __construct(
        private BoardRepository $boardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    /**
     * @param RemoveMemberCommand $command
     */
    public function __invoke(RemoveMemberCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::boardRemoveMember())
            || 0 === \strcasecmp($command->getUserId(), $command->getMemberId())) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);
        $board->removeMember(UserId::fromString($command->getMemberId()));

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
