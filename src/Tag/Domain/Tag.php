<?php

declare(strict_types=1);

namespace FC\Tag\Domain;

use Assert\Assert;
use FC\Shared\Domain\Aggregate\AggregateRoot;
use FC\Shared\Domain\Aggregate\EventRecording;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Tag\TagId;
use FC\Tag\Domain\Event\TagColorWasChanged;
use FC\Tag\Domain\Event\TagNameWasChanged;
use FC\Tag\Domain\Event\TagWasCreated;

final class Tag implements AggregateRoot
{
    use EventRecording;

    /**
     * @param TagId $id
     * @param BoardId $boardId
     * @param TagName $name
     * @param TagColor $color
     */
    public function __construct(
        private TagId $id,
        private BoardId $boardId,
        private TagName $name,
        private TagColor $color,
    ) {
    }

    /**
     * @param TagId $id
     * @param BoardId $boardId
     * @param TagName $name
     * @param TagColor $color
     * @return static
     */
    public static function create(TagId $id, BoardId $boardId, TagName $name, TagColor $color): self
    {
        $tag = new self($id, $boardId, $name, $color);
        $tag->recordThat(
            new TagWasCreated($id->asString(), $boardId->asString(), $name->asString(), $color->asString())
        );
        return $tag;
    }

    /**
     * @return TagId
     */
    public function id(): TagId
    {
        return $this->id;
    }

    /**
     * @param TagName $newName
     */
    public function changeName(TagName $newName): void
    {
        Assert::that($newName->asString())->notEq($this->name->asString());

        $this->name = $newName;

        $this->recordThat(new TagNameWasChanged($this->id->asString(), $newName->asString()));
    }

    /**
     * @param TagColor $newColor
     */
    public function changeColor(TagColor $newColor): void
    {
        Assert::that($newColor->asString())->notEq($this->color->asString());

        $this->color = $newColor;

        $this->recordThat(new TagColorWasChanged($this->id->asString(), $newColor->asString()));
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
