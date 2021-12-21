<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardColor;
use FC\Domain\Aggregate\BoardAggregate\BoardDescription;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardName;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class UpdateBoardCommandHandler implements CommandHandler
{
    public function __construct(
        private BoardRepository $boardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(UpdateBoardCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::boardEdit())) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);

        if (null !== $command->getName()) {
            $board->changeName(BoardName::fromString($command->getName()));
        }

        if (null !== $command->getColor()) {
            $board->changeColor(BoardColor::fromString($command->getColor()));
        }

        if (null !== $command->getDescription()) {
            $board->changeDescription(BoardDescription::fromString($command->getDescription()));
        }

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
