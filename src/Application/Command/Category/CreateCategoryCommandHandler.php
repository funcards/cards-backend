<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CategoryAggregate\Category;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryName;
use FC\Domain\Aggregate\CategoryAggregate\CategoryPosition;
use FC\Domain\Aggregate\CategoryAggregate\CategoryRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class CreateCategoryCommandHandler implements CommandHandler
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
     * @param CreateCategoryCommand $command
     */
    public function __invoke(CreateCategoryCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::categoryAdd())) {
            throw AccessDeniedException::new();
        }

        $category = Category::create(
            CategoryId::fromString($command->getCategoryId()),
            $boardId,
            CategoryName::fromString($command->getName()),
            CategoryPosition::fromInt($command->getPosition()),
        );

        $this->categoryRepository->save($category);
        $this->eventBus->publish(...$category->releaseEvents());
    }
}
