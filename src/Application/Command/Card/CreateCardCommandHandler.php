<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CardAggregate\Card;
use FC\Domain\Aggregate\CardAggregate\CardContent;
use FC\Domain\Aggregate\CardAggregate\CardId;
use FC\Domain\Aggregate\CardAggregate\CardName;
use FC\Domain\Aggregate\CardAggregate\CardPosition;
use FC\Domain\Aggregate\CardAggregate\CardRepository;
use FC\Domain\Aggregate\CardAggregate\CardTags;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class CreateCardCommandHandler implements CommandHandler
{
    public function __construct(
        private CardRepository $cardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(CreateCardCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::cardAdd())) {
            throw AccessDeniedException::new();
        }

        $card = Card::create(
            CardId::fromString($command->getCardId()),
            $boardId,
            CategoryId::fromString($command->getCategoryId()),
            CardName::fromString($command->getName()),
            CardContent::fromString($command->getContent()),
            CardPosition::fromInt($command->getPosition()),
            CardTags::from(...$command->getTags()),
        );

        $this->cardRepository->save($card);
        $this->eventBus->publish(...$card->releaseEvents());
    }
}
