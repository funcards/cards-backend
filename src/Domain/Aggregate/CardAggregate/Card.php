<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Event\Card\CardCategoryWasChanged;
use FC\Domain\Event\Card\CardContentWasChanged;
use FC\Domain\Event\Card\CardNameWasChanged;
use FC\Domain\Event\Card\CardPositionWasChanged;
use FC\Domain\Event\Card\CardTagsWasChanged;
use FC\Domain\Event\Card\CardWasCreated;

final class Card implements AggregateRoot
{
    use EventRecording;

    /**
     * @param CardId $id
     * @param BoardId $boardId
     * @param CategoryId $categoryId
     * @param CardName $name
     * @param CardContent $content
     * @param CardPosition $position
     * @param CardTags $tags
     */
    public function __construct(
        private CardId $id,
        private BoardId $boardId,
        private CategoryId $categoryId,
        private CardName $name,
        private CardContent $content,
        private CardPosition $position,
        private CardTags $tags,
    ) {
    }

    /**
     * @param CardId $cardId
     * @param BoardId $boardId
     * @param CategoryId $categoryId
     * @param CardName $name
     * @param CardContent $content
     * @param CardPosition $position
     * @param CardTags $tags
     * @return static
     */
    public static function create(
        CardId $cardId,
        BoardId $boardId,
        CategoryId $categoryId,
        CardName $name,
        CardContent $content,
        CardPosition $position,
        CardTags $tags,
    ): self {
        $card = new self($cardId, $boardId, $categoryId, $name, $content, $position, $tags);
        $card->recordThat(
            new CardWasCreated(
                $cardId->asString(),
                $boardId->asString(),
                $categoryId->asString(),
                $name->asString(),
                $content->asString(),
                $position->asInt(),
                $tags->toArray(),
            )
        );
        return $card;
    }

    /**
     * @return CardId
     */
    public function id(): CardId
    {
        return $this->id;
    }

    /**
     * @param CategoryId $newCategoryId
     */
    public function changeCategory(CategoryId $newCategoryId): void
    {
        if ($this->categoryId->isEqualTo($newCategoryId)) {
            return;
        }

        $this->categoryId = $newCategoryId;

        $this->recordThat(
            new CardCategoryWasChanged($this->id->asString(), $this->boardId->asString(), $newCategoryId->asString())
        );
    }

    /**
     * @param CardName $newName
     */
    public function changeName(CardName $newName): void
    {
        if ($this->name->isEqualTo($newName)) {
            return;
        }

        $this->name = $newName;

        $this->recordThat(
            new CardNameWasChanged($this->id->asString(), $this->boardId->asString(), $newName->asString())
        );
    }

    /**
     * @param CardContent $newContent
     */
    public function changeContent(CardContent $newContent): void
    {
        if ($this->content->isEqualTo($newContent)) {
            return;
        }

        $this->content = $newContent;

        $this->recordThat(
            new CardContentWasChanged($this->id->asString(), $this->boardId->asString(), $newContent->asString())
        );
    }

    /**
     * @param CardPosition $newPosition
     */
    public function changePosition(CardPosition $newPosition): void
    {
        if ($this->position->isEqualTo($newPosition)) {
            return;
        }

        $this->position = $newPosition;

        $this->recordThat(
            new CardPositionWasChanged($this->id->asString(), $this->boardId->asString(), $newPosition->asInt())
        );
    }

    /**
     * @param CardTags $newTags
     */
    public function changeTags(CardTags $newTags): void
    {
        if ($this->tags->isEqualTo($newTags)) {
            return;
        }

        $this->tags = $newTags;

        $this->recordThat(
            new CardTagsWasChanged($this->id->asString(), $this->boardId->asString(), $newTags->toArray())
        );
    }

    /**
     * @param TagId $tagId
     */
    public function addTag(TagId $tagId): void
    {
        $this->changeTags($this->tags->add($tagId));
    }

    /**
     * @param TagId $tagId
     */
    public function removeTag(TagId $tagId): void
    {
        $this->changeTags($this->tags->remove($tagId));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Card: [id => %s, boardId => %s, categoryId => %s, name => %s, position => %s, tags => %s]',
            $this->id,
            $this->boardId,
            $this->categoryId,
            $this->name,
            $this->position,
            $this->tags,
        );
    }
}
