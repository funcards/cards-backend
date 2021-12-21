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
use FC\Domain\ValueObject\Roles;

final class AddMemberCommandHandler implements CommandHandler
{
    public function __construct(
        private BoardRepository $boardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(AddMemberCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::boardAddMember())) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);
        $board->addMember(UserId::fromString($command->getMemberId()), Roles::fromString(...$command->getRoles()));

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
