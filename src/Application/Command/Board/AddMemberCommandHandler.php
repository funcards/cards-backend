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
        private readonly BoardRepository $boardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(AddMemberCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::BoardAddMember)) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);
        $board->addMember(UserId::fromString($command->memberId), Roles::from(...$command->roles));

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
