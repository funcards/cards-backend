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
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(CreateCategoryCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::CategoryAdd)) {
            throw AccessDeniedException::new();
        }

        $category = Category::create(
            CategoryId::fromString($command->categoryId),
            $boardId,
            CategoryName::fromString($command->name),
            CategoryPosition::fromInt($command->position),
        );

        $this->categoryRepository->save($category);
        $this->eventBus->publish(...$category->releaseEvents());
    }
}
