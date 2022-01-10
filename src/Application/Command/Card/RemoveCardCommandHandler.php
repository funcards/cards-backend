<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CardAggregate\CardId;
use FC\Domain\Aggregate\CardAggregate\CardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class RemoveCardCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function __invoke(RemoveCardCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::CardRemove)) {
            throw AccessDeniedException::new();
        }

        $card = $this->cardRepository->get(CardId::fromString($command->cardId));

        $this->cardRepository->remove($card);
    }
}
