<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
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

final class UpdateCardCommandHandler implements CommandHandler
{
    /**
     * @param CardRepository $cardRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EventBus $eventBus
     */
    public function __construct(
        private CardRepository $cardRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    /**
     * @param UpdateCardCommand $command
     */
    public function __invoke(UpdateCardCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::cardEdit())) {
            throw AccessDeniedException::new();
        }

        $card = $this->cardRepository->get(CardId::fromString($command->getCardId()));

        if (null !== $command->getCategoryId()) {
            $card->changeCategory(CategoryId::fromString($command->getCategoryId()));
        }

        if (null !== $command->getName()) {
            $card->changeName(CardName::fromString($command->getName()));
        }

        if (null !== $command->getContent()) {
            $card->changeContent(CardContent::fromString($command->getContent()));
        }

        if (null !== $command->getPosition()) {
            $card->changePosition(CardPosition::fromInt($command->getPosition()));
        }

        if (null !== $command->getTags()) {
            $card->changeTags(CardTags::from(...$command->getTags()));
        }

        $this->cardRepository->save($card);
        $this->eventBus->publish(...$card->releaseEvents());
    }
}
