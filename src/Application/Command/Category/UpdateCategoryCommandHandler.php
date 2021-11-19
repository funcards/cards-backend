<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryName;
use FC\Domain\Aggregate\CategoryAggregate\CategoryPosition;
use FC\Domain\Aggregate\CategoryAggregate\CategoryRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class UpdateCategoryCommandHandler implements CommandHandler
{
    /**
     * @param CategoryRepository $categoryRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EventBus $eventBus
     */
    public function __construct(
        private CategoryRepository $categoryRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    /**
     * @param UpdateCategoryCommand $command
     */
    public function __invoke(UpdateCategoryCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::categoryEdit())) {
            throw AccessDeniedException::new();
        }

        $category = $this->categoryRepository->get(CategoryId::fromString($command->getCategoryId()));

        if (null !== $command->getName()) {
            $category->changeName(CategoryName::fromString($command->getName()));
        }

        if (null !== $command->getPosition()) {
            $category->changePosition(CategoryPosition::fromInt($command->getPosition()));
        }

        $this->categoryRepository->save($category);
        $this->eventBus->publish(...$category->releaseEvents());
    }
}
