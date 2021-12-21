<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CategoryAggregate;

use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Event\Category\CategoryNameWasChanged;
use FC\Domain\Event\Category\CategoryPositionWasChanged;
use FC\Domain\Event\Category\CategoryWasCreated;

final class Category implements AggregateRoot
{
    use EventRecording;

    public function __construct(
        private CategoryId $id,
        private BoardId $boardId,
        private CategoryName $name,
        private CategoryPosition $position,
    ) {
    }

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

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function changeName(CategoryName $newName): void
    {
        if ($this->name->isEqualTo($newName)) {
            return;
        }

        $this->name = $newName;

        $this->recordThat(
            new CategoryNameWasChanged($this->id->asString(), $this->boardId->asString(), $newName->asString())
        );
    }

    public function changePosition(CategoryPosition $newPosition): void
    {
        if ($this->position->isEqualTo($newPosition)) {
            return;
        }

        $this->position = $newPosition;

        $this->recordThat(
            new CategoryPositionWasChanged($this->id->asString(), $this->boardId->asString(), $newPosition->asInt())
        );
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
