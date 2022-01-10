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
        private readonly CardRepository $cardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(CreateCardCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::CardAdd)) {
            throw AccessDeniedException::new();
        }

        $card = Card::create(
            CardId::fromString($command->cardId),
            $boardId,
            CategoryId::fromString($command->categoryId),
            CardName::fromString($command->name),
            CardContent::fromString($command->content),
            CardPosition::fromInt($command->position),
            CardTags::from(...$command->tags),
        );

        $this->cardRepository->save($card);
        $this->eventBus->publish(...$card->releaseEvents());
    }
}
