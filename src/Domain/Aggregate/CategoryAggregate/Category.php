<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CategoryAggregate;

use Assert\Assert;
use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Event\Category\CategoryNameWasChanged;
use FC\Domain\Event\Category\CategoryPositionWasChanged;
use FC\Domain\Event\Category\CategoryWasCreated;

final class Category implements AggregateRoot
{
    use EventRecording;

    /**
     * @param CategoryId $id
     * @param BoardId $boardId
     * @param CategoryName $name
     * @param CategoryPosition $position
     */
    public function __construct(
        private CategoryId $id,
        private BoardId $boardId,
        private CategoryName $name,
        private CategoryPosition $position,
    ) {
    }

    /**
     * @param CategoryId $categoryId
     * @param BoardId $boardId
     * @param CategoryName $name
     * @param CategoryPosition $position
     * @return static
     */
    public static function create(
        CategoryId $categoryId,
        BoardId $boardId,
        CategoryName $name,
        CategoryPosition $position,
    ): self {
        $category = new self($categoryId, $boardId, $name, $position);
        $category->recordThat(
            new CategoryWasCreated(
                $categoryId->asString(),
                $boardId->asString(),
                $name->asString(),
                $position->asInt(),
            )
        );
        return $category;
    }

    /**
     * @return CategoryId
     */
    public function id(): CategoryId
    {
        return $this->id;
    }

    /**
     * @param CategoryName $newName
     */
    public function changeName(CategoryName $newName): void
    {
        Assert::that($newName->asString())->notEq($this->name->asString());

        $this->name = $newName;

        $this->recordThat(new CategoryNameWasChanged($this->id->asString(), $newName->asString()));
    }

    /**
     * @param CategoryPosition $newPosition
     */
    public function changePosition(CategoryPosition $newPosition): void
    {
        Assert::that($newPosition->asInt())->notEq($this->position->asInt());

        $this->position = $newPosition;

        $this->recordThat(new CategoryPositionWasChanged($this->id->asString(), $newPosition->asInt()));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Category [id => %s, boardId => %s, name => %s, position => %d]',
            $this->id,
            $this->boardId,
            $this->name,
            $this->position,
        );
    }
}
