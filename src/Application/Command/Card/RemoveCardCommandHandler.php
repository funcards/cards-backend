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
    /**
     * @param CardRepository $cardRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private CardRepository $cardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @param RemoveCardCommand $command
     */
    public function __invoke(RemoveCardCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::cardRemove())) {
            throw AccessDeniedException::new();
        }

        $card = $this->cardRepository->get(CardId::fromString($command->getCardId()));

        $this->cardRepository->remove($card);
    }
}
