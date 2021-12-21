<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\TagAggregate;

use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Event\Tag\TagColorWasChanged;
use FC\Domain\Event\Tag\TagNameWasChanged;
use FC\Domain\Event\Tag\TagWasCreated;

final class Tag implements AggregateRoot
{
    use EventRecording;

    public function __construct(
        private TagId $id,
        private BoardId $boardId,
        private TagName $name,
        private TagColor $color,
    ) {
    }

    public static function create(TagId $tagId, BoardId $boardId, TagName $name, TagColor $color): self
    {
        $tag = new self($tagId, $boardId, $name, $color);
        $tag->recordThat(
            new TagWasCreated($tagId->asString(), $boardId->asString(), $name->asString(), $color->asString())
        );
        return $tag;
    }

    public function id(): TagId
    {
        return $this->id;
    }

    public function changeName(TagName $newName): void
    {
        if ($this->name->isEqualTo($newName)) {
            return;
        }

        $this->name = $newName;

        $this->recordThat(
            new TagNameWasChanged($this->id->asString(), $this->boardId->asString(), $newName->asString())
        );
    }

    public function changeColor(TagColor $newColor): void
    {
        if ($this->color->isEqualTo($newColor)) {
            return;
        }

        $this->color = $newColor;

        $this->recordThat(
            new TagColorWasChanged($this->id->asString(), $this->boardId->asString(), $newColor->asString())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Tag [id => %s, boardId => %s, name => %s, color => %s]',
            $this->id,
            $this->boardId,
            $this->name,
            $this->color,
        );
    }
}
